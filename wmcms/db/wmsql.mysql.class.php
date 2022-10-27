<?php
/**
* 文件作用：数据库操作
* 数据库类
* 说明:系统底层数据库核心类
*
* @version        $Id: wmsql.class.php 2015年8月9日 12:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2016年11月13日 15:56 weimeng
*
**/
if(!defined('WMINC')){ exit("dont alone open!"); }

// 不需要初始化类，系统已经初始化了，可直接用 $wmsql 进行操作
$wmsql = new WMSql(FALSE);
class WMSql
{
	//db对象
    static public $db;
    //打开的db对象
    static public $openDB;
    //数据库ip
    static public $dbHost;
    //数据库端口
    static public $dbPort;
    //数据库名
    static public $dbName;
    //数据库前缀
    static public $dbPrefix;
    //数据库账号
    static public $dbUname;
    //数据库密码
    static public $dbUPsw;
    //数据库编码
    static public $dbCode;
    //是否检查sql
    static public $checkSql = true;
    //缓存对象
    static public $cacheSer;
	//运行的sql列表
    static public $sqlList = array();
	//最后的sql
    static public $lastSql;
    //最后的sql运行时间
    static public $lastSqlTime;
    //最后影响的数据行数
    static public $lastCount;
	//条件预处理
    static public $wherePrepare;
	//数据预处理
	static public $dataPrepare;
	//当前操作的表名
	static public $tableName;

    //用外部定义的变量初始类，并链接数据库
    function __construct()
    {
        $this->OpenDB();
    }

    /**
     * 选择数据库
     * @param 参数1，选填，选择哪一个数据库，默认为空为主数据库。
     */
    static function SelectDB($id='')
    {
    	if( isset(self::$openDB['db'.$id]) )
    	{
    		self::$db = self::$openDB['db'.$id];
    	}
    	else if( C('config.db'.$id.'.host') == '' )
    	{
    		echo '错误信息:<b style="color:red;">对不起，请检查【数据库'.$id.'】是否配置了！</b><br/>';
    		exit();
    	}
    	else
    	{
    		self::OpenDB($id);
    	}
    }
    /**
     * 打开数据库
     * @param 参数1，选填，默认为主数据库
     */
    static function OpenDB($id='')
    {
        global $C;
        $host = C('config.db'.$id.'.host');
        $port = C('config.db'.$id.'.port');
        $name = C('config.db'.$id.'.name');
        $prefix =C('config.db'.$id.'.prefix');
        $uname = C('config.db'.$id.'.uname');
        $upsw = C('config.db'.$id.'.upsw');
        $sqlCode = C('config.db'.$id.'.sqlcode');
        //数据库地址
        self::$dbHost   =  $host;
        //数据库端口
        self::$dbPort   =  $port;
        //数据库名
        self::$dbName   =  $name;
        //数据库前缀
        self::$dbPrefix   =  $prefix;
        //数据库用户名
        self::$dbUname   =  $uname;
        //数据库密码
        self::$dbUPsw   =  $upsw;
        //数据库编码
        self::$dbCode   =  $sqlCode;
        try {
            $dsn = "mysql:host=".self::$dbHost.";port=".self::$dbPort.";dbname=".self::$dbName."";
            self::$db = new PDO($dsn,  self::$dbUname, self::$dbUPsw);
            //转换数据库编码
            self::$db->exec('SET NAMES '.self::$dbCode);
            //显示错误信息
        	self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	self::$openDB['db'.$id] = self::$db;
        } catch (PDOException $e) {
            //返回错误信息
            echo '错误信息:<b style="color:red;">对不起，请检查数据库配置信息是否正确！</b><br/>';
            echo "错误详情：" . $e->getMessage() . "";
            self::$db = null;
            exit();
        }
    }

    
    /**
    * 函数作用：表名前缀替换
    * 必须字段，要替换的表名或者sql，表前缀的占位符必须为@
    **/
    static function TablePre($sql)
    {
    	//只替换一次
    	$sql = preg_replace('/@@/', self::$dbPrefix, $sql, 1);
        //检查sql数据
		$sql = self::CheckSql($sql);
        return $sql;
    }
    
    /**
     * 开启事务
     * @return boolean
     */
    static function BeginTransaction()
    {
    	return self::$db->beginTransaction();
    }
    /**
     * 提交事务
     * @return boolean
     */
    static function Commit()
    {
    	return self::$db->commit();
    }
    /**
     * 回滚事务
     * @return boolean
     */
    static function RollBack()
    {
    	return self::$db->rollBack();
    }

    /**
     * 函数作用：表名补齐
     * @param 参数1，必须，表名
     **/
    static function Table($table)
    {
    	return self::$dbPrefix.$table;
    }
    

    /**
     * 判断表是否存在
     * @param 参数1，必须，表名
     */
    static function TableExists($table)
    {
    	$data = self::Query("SHOW TABLES LIKE '".self::CheckTable($table)."'");
    	if( $data )
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    /**
     * 表如果存在就删除
     * @param 参数1，必须，表名
     */
    static function TableExistsDrop($table)
    {
    	if( self::TableExists($table) )
    	{
    		return self::Drop($table);
    	}
    	return true;
    }

    /**
     * 判断字段是否存在
     * @param 参数1，必须，表名
     * @param 参数2，必须，字段名
     */
    static function ColumExists($table,$colum)
    {
    	$where['table'] = 'information_schema.columns';
    	$where['where']['table_name'] = self::CheckTable($table);
    	$where['where']['column_name'] = $colum;
    	if( wmsql::GetCount($where) > 0 )
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    /**
     * 删除字段
     * @param 参数1，必须，表名
     * @param 参数2，必须，字段名
     */
    static function ColumDrop($table,$colum)
    {
    	return self::Exec('ALTER TABLE '.self::CheckTable($table).' DROP COLUMN `'.$colum.'`');
    }
    /**
     * 字段如果存在就删除
     * @param 参数1，必须，表名
     * @param 参数2，必须，字段名
     */
    static function ColumExistsDrop($table,$colum)
    {
    	if( self::ColumExists($table, $colum) )
    	{
    		return self::ColumDrop($table, $colum);
    	}
    	return true;
    }
    /**
     * 获得表信息
     * @param 参数1，必须，表名
     */
    static function TableInfo($table='')
    {
    	if( $table != '' )
    	{
    		$table = " like '".self::CheckTable($table)."'";
    	}
    	$data = self::Query('show table status'.$table);
    	return $data;
    }
    
    /**
     * 获得表字段信息
     * @param 参数1，必须，表名
     */
    static function TableFrom($table)
    {
    	$data = self::Query('SHOW FULL COLUMNS FROM '.self::CheckTable($table));
    	return $data;
    }
    
    /**
    * 函数作用：表名前缀替换
    * 必须字段，要替换的表名或者sql，表前缀的占位符必须为@
    **/
    static function CheckTable($tables){
    	$table = '';
        if( is_array( $tables ) )
        {
            $tableArr = $tables;
            foreach ($tableArr as $k=>$v)
            {
                //如果是数字就是格式1
                if ( is_numeric($k) )
                {
                  $table.='`'.$v.'`';
                }
                //否则进行格式2替换
                else if ( $v != '')
                {
                  $table.='`'.$k.'` as '.$v;
                }
                else
                {
                	$table.='`'.$k.'`';
                }
                //如果不是最后一个指针就加上字段分号
                if(current($tableArr) != array_pop($tableArr)){
                    $table.=',';
                }
           }
        }
        //如果需要显示的字段为字符串
        else if( trim( $tables ) =='' )
        {
            $table='*';
        }
        else
        {
            $table=$tables;
        }
        //替换表前缀
        self::$tableName = $table = str_replace('@', self::$dbPrefix, $table);

        return $table;
    }
    
    
    /**
    * 检查where条件是否是字符串或者是数组。
    * @param 参数1，必须，字符串或者数组。传入的where条件。
    * 字符串格式：id=1 and name=a
    * 数组格式1：array(id=>1,name=>a)  结果为：id=1 and name=a
    */
    static function CheckWhere($wheresqls , $connector = 'where')
	{
		if( empty($wheresqls) )
		{
			return false;
		}
    	$wheresql = '';
        //如果where条件是数组，
        if( is_array( $wheresqls ) )
        {
        	$i = 1;
            $wheresqlArr = $wheresqls;
            foreach ($wheresqlArr as $k=>$v)
            {
            	$rawkey = $k;
            	//判断键数组是否是`field`或者a.field格式，不是就加上`
            	if ( str_replace('`','',$k) == $k && str_replace('.','',$k) == $k)
            	{
            		$k = "`{$k}`";
            	}
            	
            	//判断条件是否是数组的特殊语句 array ( '执行符' , '执行条件' , '运算符or或者and' )
                if ( is_array($v) )
                {
                	//运算符查询
                	$symbol = array('+','-','=','*','/','<=','>=','<','>','<>','!=','不等于','等于','大于','小于');
                	$repSym = array('不等于'=>'<>','等于'=>'=','大于'=>'>','小于'=>'<');
                	//判断符查询
                	$un = array('string','between','and-or','and','in-id','in','nin','lnin','lin','rin','or',
                			'betweenlike','like','notlike','startlike','endlike','null');

                	//如果条件中存在运算符
                	if( in_array($v[0], $symbol) )
                	{
                		//匹配中文标签
                		if( array_key_exists( $v[0] , $repSym ) )
                		{
                			$v[0] = "{$repSym[$v[0]]}";
                		}

                		$wheresql .= self::CheckWherePrepare($k." {$v[0]} ?",$v[1]);
                	}
                	//匹配第二个运算符
                	else if( in_array($v[0], $un) )
                	{
                		switch ( $v[0] )
                		{
                			case 'string':
		                		$wheresql .= $v[1];
                				break;

                			case 'between':
								list($start,$end) = explode(',',$v[1]);
                				$wheresql .= "{$k}>{$start} and {$k}<{$end}";
                				break;
                				
                			case 'and-or':
                				$wheresql .= '( ';
                				if( is_array($v[1]) )
                				{
                					$wheresql .= self::CheckWhere( array($rawkey=>$v[1]) , '' );
                				}
                				else
                				{
                					$wheresql .= self::CheckWherePrepare($k.'=?',$v[1]);
                				}
		                		foreach ($v[2] as $andOrKey=>$andOrVal)
		                		{
		                			$wheresql .= ' or ';
		                			if( is_array($andOrVal) )
		                			{
		                				$wheresql .= self::CheckWhere( array($andOrKey=>$andOrVal) , '' );
		                			}
		                			else
		                			{
		                				$wheresql .= self::CheckWherePrepare($andOrKey.'=? ',$andOrVal);
		                			}
		                		}
		                		$wheresql .= ' )';
                				break;
                				
                			case 'and':
		                		$wheresql .= self::CheckWherePrepare('( '.$k.'=? )',$v[1]);
                				break;

                			case 'in-id':
		                		$whereLabel = str::StrToWhere( $v[1] , "'{i}'" , ',');
		                		$wheresql.= '( '.$k.' in ('.$whereLabel.') )';
                				break;
								
                			//not in
                			case 'nin':
                				$whereLabel = str::StrToWhere( '?' , "NOT FIND_IN_SET(?,{$k})" , ' or ');
                				$wheresql.= self::CheckWherePrepare('( '.$whereLabel.' )',$v[1]);
                				break;
                				//left not in
                			case 'lnin':
                				$whereLabel = str::StrToWhere( '?' , "NOT FIND_IN_SET({$k},?)" , ' or ');
                				$wheresql.= self::CheckWherePrepare('( '.$whereLabel.' )',$v[1]);
                				break;
                			case 'in':
		                		$whereLabel = str::StrToWhere( '?' , "FIND_IN_SET(?,{$k})" , ' or ');
                				$wheresql.= self::CheckWherePrepare('( '.$whereLabel.' )',$v[1]);
                				break;
                			//左外in
                			case 'lin':
								$linValArr = explode(',', $v[1]);
								$wheresql .= "(";
								foreach ( $linValArr as $lk=>$lv )
								{
			                		$wheresql.= self::CheckWherePrepare("FIND_IN_SET({$k},?)",$lv);
			                		if( !IsLast($linValArr,$lk) )
			                		{
			                			$wheresql.= " or ";
			                		}
								}
			                	$wheresql .= ")";
                				break;
                			//右外in
                			case 'rin':
								$linValArr = explode(',', $v[1]);
								$wheresql .= "(";
								foreach ( $linValArr as $lk=>$lv )
								{
			                		$wheresql.= self::CheckWherePrepare("FIND_IN_SET(?,{$k})",$lv);
			                		if( !IsLast($linValArr,$lk) )
			                		{
			                			$wheresql.= " or ";
			                		}
								}
			                	$wheresql .= ")";
                				break;

                			case 'or':
								$orStr = '';
								$orArr = explode(',',$v[1]);
								$oI = 1;
								foreach($orArr as $oKey=>$oVal)
								{
									$orStr .= self::CheckWherePrepare('?',$oVal);
									if( $oI < count($orArr))
									{
										$orStr .= ',';
									}
									$oI++;
								}
                				$whereLabel = str::StrToWhere( $orStr, "{$k} = {i}" , ' or ');
                				$wheresql.= '( '.$whereLabel.' )';
                				break;
                			
                			case 'null':
		                		$wheresql.= $k.' '.$v[1]." null and {$k} != ''";
                				break;
                			//区间开头
                			case 'betweenlike':
								list($start,$end) = explode(',',$v[1]);
                				$wheresql .= "{$k} regexp '^[{$start}-{$end}]'";
                				break;
                				
                			//模糊查询
                			case 'startlike':
                			case 'endlike':
                			case 'like':
                			case 'notlike':
                				$whereLabel = '';
                				//每个值的链接符号
                				$ValSymbol = 'or';
                				//每个字段的链接符
                				$FieldSymbol = 'or';
                				//模糊查询的字段数组
                				$fieldArr = array($k);
                				
                				//如果不是数组就直接分割条件
                				if ( !is_array($v[1]) )
                				{
                					$valArr = explode( ',' , $v[1] );
                				}
                				else
                				{
                					//判断多字段是否为字符串，并且不为空
                					if( isset($v[1]['field']) && $v[1]['field'] != '' )
                					{
	                					if ( !is_array($v[1]['field']) )
	                					{
	                						$fieldArr = array_merge($fieldArr , explode( ',' , $v[1]['field'] ) );
	                					}
	                					else
	                					{
	                						$fieldArr[] = $v[1]['field'];
	                					}
	                					//检查每个字段的链接符
	                					if( isset($v[1]['field_symbol']) && $v[1]['field_symbol'] != '' )
	                					{
	                						$FieldSymbol = $v[1]['field_symbol'];
	                					}
                					}
                					//检查链接符是否存在，每个条件的链接符
                					if( isset($v[1]['val_symbol']) && $v[1]['val_symbol'] != '' )
                					{
                						$ValSymbol = $v[1]['val_symbol'];
                					}
                					
                					$valArr = explode( ',' , $v[1]['val'] );
                				}
                				
                				//循环字段
                				$likeI = 1;
                				foreach ($fieldArr as $FKey=>$FVal)
                				{
                					$whereLabel = '';
                					//循环条件
                					$j = 1;
                					foreach ( $valArr as $VKey=>$VVal )
                					{
                						//以某个字符开始
                						if( $v[0] == 'startlike' )
                						{
											$orVal = '%'.$VVal;
                						}
                						//某个字符结束
                						else if( $v[0] == 'endlike' )
                						{
											$orVal = $VVal.'%';
                						}
                						else
                						{
											$orVal = '%'.$VVal.'%';
                						}

                						//不包含某个字符串
                						if( $v[0] == 'notlike' )
                						{
                							$whereLabel .= self::CheckWherePrepare($FVal." not like ?",$orVal);
                						}
                						else
                						{
                							$whereLabel .= self::CheckWherePrepare($FVal." like ?",$orVal);
                						}
                						if( $j < count($valArr) )
                						{
                							$whereLabel.=' '.$ValSymbol.' ';
                						}
                						$j++;
                					}
                					$wheresql.= '( '.$whereLabel.' )';
                					//检查每个条件
                					if( $FieldSymbol != '' && $likeI < count($fieldArr) )
                					{
                						$wheresql.=' '.$FieldSymbol.' ';
                					}
                					$likeI++;
                				}
								$wheresql = '( '.$wheresql.' )';
                				break;
                		}
                	}
                	else
                	{
                		if( (isset($v[0]) && $v[0] != '') && (isset($v[1]) && $v[1] != '') )
                		{
                			$wheresql.= self::CheckWherePrepare($k." {$v[0]} ?",$v[1]);
                		}
                	}
                }
                else
                {
                	$wheresql.= self::CheckWherePrepare($k."=?",$v);
                }
                
            	
                //如果不是最后一个指针就加上字段分号
                if( $i < count($wheresqlArr) )
                {
					$wheresql.=' and ';
				}
				$i++;
			}
			$wheresql=' '.$connector.' '.$wheresql;
        }
        //如果where是字符串并且不为空
        else if( trim( $wheresqls ) <> '' && ( str_ireplace('where','',$wheresqls) == $wheresqls) )
        {
            
            $wheresql = ' '.$connector.' '.self::CheckSql($wheresqls);
        }
        else
        {
            $wheresql = $wheresqls;
        }
        
        return $wheresql;
    }
    
    /**
     * 检查where条件参数绑定
     * @param string $con 表达式
     * @param string $con 绑定的参数值
     */
    static function CheckWherePrepare($con,$val)
    {
    	if( self::$wherePrepare === false )
    	{
    		$con = str_replace('?', "'{$val}'", $con);
    	}
    	else
    	{
    		self::$wherePrepare[] = $val;
    	}
    	return $con;
    }
    
    /**
    * 检查要显示的字段，
    * @param 参数1，必须，字符串或者数组。字符串或者数组，
    * 字符串格式：字段1,字段2
    * 数组格式1：array(字段1，字段2)  结果为：字段1,字段2
    * 数组格式2：array(字段1=>a，字段2=>b)  结果为：a,b
    */
    static function CheckField($fields){
    	$field = '';
        //如果需要显示的字段为数组，
        if( is_array( $fields ) )
        {
            $fieldArr = $fields;
            $i = 1;
            $count = count($fieldArr);
            foreach ($fieldArr as $k=>$v)
            {
                //如果是数字就是格式1
                if ( is_numeric($k))
                {
                  $field.=$v;
                }
                //否则进行格式2替换
                else if ($v == '')
                {
                	$field.=$k;
                }
                else
                {
                  $field.=$k.' as '.$v;
                }
                
                //如果不是最后一个指针就加上字段分号
                if( $i != $count )
                {
                    $field.=',';
                }
                $i++;
           }
        }
        //如果需要显示的字段为字符串
        else if( trim( $fields ) =='' )
        {
            $field='*';
        }
        else
        {
            $field = $fields;
        }

        //替换表前缀
        $field=str_replace('@', self::$dbPrefix, $field);
        
        return $field;
    }

    
    /**
     * 检查左外链接，
     * @param 参数1，必须，数组或者字符串
     */
    static function CheckLeft($lefts){
    	$left = '';

        //如果需要显示的字段为数组，
        if( is_array( $lefts ) )
        {
            $leftArr = $lefts;

            foreach ($leftArr as $k=>$v)
            {
            	$symbol = 'left';
    			//如果值是数组
            	if( is_array($v) )
            	{
            		$symbol = $v[0];
            		$v = $v[1];
            	}

                $left.=' '.$symbol.' join '.$k.' on '.$v;
			}
        }
        //如果左外链接的字段为字符串
        else if( trim( $lefts ) == '' )
        {
            $left = '';
        }
        else
        {
            $left = $lefts;
        }
		$left = str_replace( '@' , self::$dbPrefix , $left );
        return $left;
    }
    
    
    /**
    * 检查排序的sql，
    * @param 参数1，必须，字符串。
    */
    static function CheckOrder($orders){
    	$order = '';
        //排序不为空，
        if( is_string($orders) && trim( $orders ) != '' )
        {
            $order = ' order by '.$orders;
        }
        return $order;
    }
    
    
    /**
    * 检查输出显示的条数，
    * @param 参数1，必须，字符串。
    */
    static function Checklimit($limits){
    	$limit = '';
        //分页不为空
        if( is_string($limits) && trim( $limits ) != '' )
        {
            $limit = ' limit '.$limits;
        }
        return $limit;
    }
    
    
    /**
     * 获得分页的limit
     * @param 参数1，必须，每页多少条
     * @param 参数2，选填，当前是第多少页
     */
    static function GetLimit($limit,$page=1)
    {
    	return ($page-1) * $limit.','.$limit;
    }

    
    /**
     * 分组检查，
     * @param 参数1，必须，字符串。
     */
    static function CheckGroup($groups){
    	$group = '';
    	//如果需要显示的字段为数组，
    	if( trim( $groups ) != '' )
    	{
    		$group=' group by '.$groups;
    	}
    
    	return $group;
    }

    /**
    * 作  用：查询一条记录
    * 参 数1：必须，数组
    * table：表名
    * left：左外链接
    * where：条件语句
    * field：字段名，默认为全部字段
    * order：排序方式
    * limit：显示多少条
    * 返回值：数组 
    **/
    static function GetOne($wheresql)
    {
        $field = '*';
        $table = $left = $where = $order = '';
        //字段检查
        if( isset($wheresql['field']) )
        {
        	$field = self::CheckField($wheresql['field']);
        }
        //表检查
        if( isset($wheresql['table']) )
        {
        	$table = self::CheckTable($wheresql['table']);
        }
        //左外链接
        if( isset($wheresql['left']) )
        {
        	$left = self::CheckLeft($wheresql['left']);
        }
        //where条件查询
        if( isset($wheresql['where']) )
        {
        	$where = self::CheckWhere($wheresql['where']);
        }
        //order条件检查
        if( isset($wheresql['order']) )
        {
			$order  = self::CheckOrder($wheresql['order']);
        }

        //检查sql语句的安全性
        $sql = self::CheckSql('SELECT '.$field.' from '.$table.$left.$where.$order.' limit 1');

        return self::Query( $sql , '1');
    }


    /**
    * 作  用：随机条数数据
    * @param 参数1，必须，条件数组
    * @param 参数2，选填，是否只读取一条数据
    **/
    static function Rand( $wheresql , $isOne = '1')
    {
		$field = '*';
		$table = $left = $where = $limit = $group = '';
        //字段检查
		if( isset($wheresql['field']) )
		{
			$field = self::CheckField($wheresql['field']);
		}
        //表检查
		if( isset($wheresql['table']) )
		{
			$table = self::CheckTable($wheresql['table']);
		}
        //左外链接
		if( isset($wheresql['left']) )
		{
			$left = self::CheckLeft($wheresql['left']);
		}
        //where条件查询
		if( isset($wheresql['where']) )
		{
			$where = self::CheckWhere($wheresql['where']);
		}
        //order条件检查
		if( isset($wheresql['limit']) )
		{
			$limit  = self::CheckLimit($wheresql['limit']);
		}
        //group条件检查
		if( isset($wheresql['group']) )
		{
			$group = self::CheckGroup($wheresql['group']);
		}

        //检查sql语句的安全性
        $sql = self::CheckSql('SELECT '.$field.' from '.$table.$left.$where.' ORDER BY Rand() '.$group.$limit);

        return self::Query( $sql , $isOne);
    }
    
    
    /**
    * 作  用：随机一条数据
    * @param 参数1，必须，条件数组
    **/
    static function RandOne($wheresql)
    {
    	$wheresql['limit'] = '1';  
        return self::Rand( $wheresql , '1');
    }


    /**
    * 作  用：查询所有符合条件的记录
    * 参 数1：必须，数组
    * table：表名
    * left：左外链接
    * where：条件语句
    * field：字段名，默认为全部字段
    * order：排序方式
    * limit：显示多少条
    * 返回值：数组 
    **/
    static function GetAll($wheresql)
    {
    	$table = $left = $where = $order = $limit = $group = '';
    	$field = '*';
        //字段检查
        if( isset($wheresql['field']) )
        {
        	$field = self::CheckField($wheresql['field']);
        }
        //表检查
        if( isset($wheresql['table']) )
        {
        	$table = self::CheckTable($wheresql['table']);
        }
        //左外链接
        if( isset($wheresql['left']) )
        {
        	$left = self::CheckLeft($wheresql['left']);
        }
        //where条件查询
        if( isset($wheresql['where']) )
        {
        	$where = self::CheckWhere($wheresql['where']);
        }
        //order条件检查
        if( isset($wheresql['order']) )
        {
        	$order = self::CheckOrder($wheresql['order']);
        }
        //limit条件检查
        if( isset($wheresql['limit']) )
        {
        	$limit = self::Checklimit($wheresql['limit']);
        }
        //group条件检查
        if( isset($wheresql['group']) )
        {
        	$group = self::CheckGroup($wheresql['group']);
        }

        //检查sql语句的安全性
        $sql = 'SELECT '.$field.' from '.$table.$left.$where.$group.$order.$limit;

        return self::Query( $sql );
    }
    
    
    /**
    * 作  用：查询符合条件的记录行数
    * 参 数1：必须，条件语句
    * @param 参数2：选填，求条数的字段名，默认为全部。
    * 返回值：数字
    **/
    static function GetCount($wheresql , $field = '*' , $isLeft = true)
    {
    	$table = $where = $group = '';
        //表检查
        $table = self::CheckTable($wheresql['table']);
        //左外链接
        if( $isLeft == false || !isset($wheresql['left']) )
        {
        	$left = '';
        }
        else
        {
        	$left = self::CheckLeft($wheresql['left']);
        }
        //where条件查询
        if( isset($wheresql['where']) )
        {
        	$where = self::CheckWhere($wheresql['where']);
        }
        //group条件检查
        if( isset($wheresql['group']) )
        {
        	$group = self::CheckGroup($wheresql['group']);
        }

        //检查sql语句的安全性
        if( $group != '' )
        {
        	self::$checkSql = false;
        	$sql = 'SELECT COUNT(*) AS count FROM (SELECT count('.$field.') as count from '.$table.$left.$where.$group.') AS a limit 1';
        }
        else
        {
        	$sql = self::CheckSql('SELECT count('.$field.') as count from '.$table.$left.$where.$group.' limit 1');
        }

        return self::Query( $sql , '2');
    }


    /**
    * 作  用：字段求和
    * 参 数1：必须，条件语句
    **/
    static function GetSum($wheresql)
    {
    	$field = '*';
    	$table = $left = $where = $group = '';
        //字段检查
        if( isset($wheresql['field']) )
        {
        	$field = self::CheckField($wheresql['field']);
        }
        //表检查
        if( isset($wheresql['table']) )
        {
        	$table = self::CheckTable($wheresql['table']);
        }
        //左外链接
        if( isset($wheresql['left']) )
        {
        	$left = self::CheckLeft($wheresql['left']);
        }
        //where条件查询
        if( isset($wheresql['where']) )
        {
        	$where = self::CheckWhere($wheresql['where']);
        }
        //group条件检查
        if( isset($wheresql['group']) )
        {
        	$group = self::CheckGroup($wheresql['group']);
        }
        //检查sql语句的安全性
        $sql=self::CheckSql('SELECT sum('.$field.') as sum from '.$table.$left.$where.$group);

        return intval(self::Query( $sql , '3'));
    }

    /**
     * 获得缓存对象
     * @return cache
     */
    static function GetCacheSer()
    {
    	
    	if( self::$cacheSer )
    	{
    		$cacheSer = self::$cacheSer;
    	}
    	else
    	{
    		global $cacheSer;
    		self::$cacheSer = $cacheSer;
    	}
    	return $cacheSer;
    }
    
    
    /**
     * 清空当前表设置的参数
     */
    static function Clear()
    {
		self::$tableName = '';
		self::$dataPrepare = array();
		self::$wherePrepare = array();
		self::$checkSql = true;
    }
    
    
    /**
     * 作  用：执行查询语句
     * 参 数1：必须，sql语句
     * @param 参数2：选填，0为返回true或者false ,1为只返回一条数据，2为返回count，3为求和sun，默认为全部返回
     * 返回值：数组
     **/
    static function Query( $sql , $attr = '')
    {
    	$sqlList = array();
		$cacheSer = self::GetCacheSer();
    	$sql = self::TablePre($sql);
		//最后执行的sql
    	self::$lastSql = $sql;
    	
		//还原最后执行的sql
		if( self::$wherePrepare )
		{
			foreach(self::$wherePrepare as $k=>$v)
			{
				self::$lastSql = preg_replace('/\?/', "'{$v}'", self::$lastSql , 1);
			}
		}
		
		//如果缓存表名为空就设置单独文件夹存放
		if( self::$tableName == '' )
		{
			self::$tableName = 'sql';
		}

		//开启了缓存
		if( $cacheSer->sqlOpen && !WMMANAGER )
		{
			//缓存名字
			$cacheName = '/'.md5(Encrypt(self::$tableName)).'/'.md5(Encrypt(self::$lastSql));
			$cacheContent = $cacheSer->GetSql($cacheName);
			//如果不是后台管理,且有缓存内容
			if( $cacheContent && !WMMANAGER)
			{
				//清空当前表和表数据
				self::Clear();
				return $cacheContent;
			}
		}
		

		//sql运行开始时间
		$sqlList['start'] = GetMicroTime();
		//?相当于操作索引数组，是按所引顺序找的点位符
		$stmt = self::$db->prepare($sql);
		try
		{
			//执行预处理查询
			if( self::$wherePrepare )
			{
				$rs = $stmt->execute(self::$wherePrepare);
			}
			else
			{
				$rs = $stmt->execute();
			}
			//sql运行结束时间
			$sqlList['end'] = GetMicroTime();
			//记录到sql列表
			$sqlList['sql'] = self::$lastSql;
			//记录到sql列表
			self::$sqlList[] = $sqlList;
			
			//清空当前表和表数据
			self::Clear();
			
			//抛出异常
			if( $rs === false )
			{
				throw new Exception();
			}
			else
			{
				//直接返回结果
				if( $attr == 'rs' )
				{
					return true;
				}
				
				//只取列名
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//直接返回全部
				$row = $stmt->fetchall();
				
				if ( DEVELOPER )
				{
					echo 'SQL：'.self::$lastSql.'<br/><br/>';
				}
				if( !empty($row) )
				{
    				switch ( $attr )
    				{
    					case '1':
    						if( class_exists('str') )
    						{
    							$arr = str::Escape($row[0]);
    						}
    						else
    						{
    							$arr = $row[0];
    						}
    						break;
    						 
    					case '2':
    						$arr = $row[0]['count'];
    						break;
    						 
    					case '3':
    						$arr = $row[0]['sum'];
    						break;
    						 
    					default:
    						if( class_exists('str') )
    						{
    							foreach ($row as $k=>$v)
    							{
    								$arr[$k] = str::Escape($v);
    							}
    						}
    						else
    						{
    							$arr = $row;
    						}
    						break;
    				}
					
    				//写入Sql缓存
    				if(	$cacheSer->sqlOpen && !WMMANAGER)
    				{
    					$cacheSer->SetSql($cacheName , $arr , $cacheSer->sqlCacheTime);
    				}
    				return $arr;
    			}
    			else
    			{
    				return false;
    			}
    		}
    	}
    	//捕获异常
    	catch(Exception $e)
    	{
    		if( $attr == 'rs' )
    		{
    			return false;
    		}
    		else
    		{
	    		// 异常的sql语句
	    		$errinfo['sql']=self::$lastSql;
	    		// 返回异常信息
	    		$errinfo['msg']=$e->getMessage();
	    		// 返回异常代码
	    		$errinfo['code']=$e->getCode();
	    		// 返回异常文件
	    		$errinfo['file']=$e->getFile();
	    		// 返回异常行数
	    		$errinfo['line']=$e->getLine();
	    		// 返回异常数组
	    		$errinfo['trace']=$e->getTrace();
	    		// 返回异常数组
	    		$errinfo['errinfo']=$e->errorInfo;
	    		//错误处理机制
	    		self::SqlErr($errinfo);
    		}
    	}
    }
    

    /**
    * 作  用：执行更新语句
    * 参 数1：必须，执行的完整sql
    * 返回值：true or false
    **/
    static function Exec($sql , $attr = '')
    {
    	$sqlList = array();
    	$cacheSer = self::GetCacheSer();
        //表前缀替换
        $sql = self::TablePre($sql);
        self::$lastSql = $sql;
        
		//还原最后执行的sql
		if( self::$dataPrepare )
		{
			foreach(self::$dataPrepare as $k=>$v)
			{
				self::$lastSql = preg_replace('/\?/', "'{$v}'", self::$lastSql , 1);
			}
		}
		if( self::$wherePrepare )
		{
			foreach(self::$wherePrepare as $k=>$v)
			{
				self::$lastSql = preg_replace('/\?/', "'{$v}'", self::$lastSql , 1);
			}
			if( self::$dataPrepare )
			{
				self::$dataPrepare = array_merge(self::$dataPrepare,self::$wherePrepare);
			}
			else
			{
				self::$dataPrepare = self::$wherePrepare;
			}
		}
		
		//sql运行开始时间
		$sqlList['start'] = GetMicroTime();
		//?相当于操作索引数组，是按所引顺序找的点位符
		$stmt = self::$db->prepare($sql);
        try
        {
            //执行查询
			if( self::$dataPrepare )
			{
				$result = $stmt->execute(self::$dataPrepare);
			}
			else
			{
				$result = $stmt->execute();
			}
			//sql运行结束时间
			$sqlList['end'] = GetMicroTime();
			//记录到sql列表
			$sqlList['sql'] = self::$lastSql;
			//记录到sql列表
			self::$sqlList[] = $sqlList;
			
			//影响的行数
			self::$lastCount = $stmt->rowCount();
			//预处理数据置空
			self::Clear();
			
        	if( $result === false)
        	{
		        throw new Exception();
		        return false;
        	}
        	else
        	{
		        if ( DEVELOPER )
		        {
		        	echo 'SQL：'.self::$lastSql.'<br/><br/>';
		        }
		        switch ( $attr )
		        {
				    case '1':
						return self::$db->lastInsertId();
						break;
						
					default:
						return true;
						break;
				}
			}
        }
        //捕获异常
        catch(Exception $e)
        {
            // 异常的sql语句
            $errinfo['sql']=self::$lastSql;
            // 返回异常信息 
            $errinfo['msg']=$e->getMessage();
            // 返回异常代码
            $errinfo['code']=$e->getCode();
            // 返回异常代码
            $errinfo['file']=$e->getFile();
            // 返回异常行数
            $errinfo['line']=$e->getLine();
            // 返回异常数组
            $errinfo['trace']=$e->getTrace();
    		// 返回异常数组
    		$errinfo['errinfo']=$e->errorInfo;
            //错误处理机制
            self::SqlErr($errinfo);
        }
    }
    

    /**
    * 作  用：数据库更新操作
    * 参 数1：必须，表名
    * 参 数2：必须，需要修改的字段值
    * 参 数3：必须，条件查询语句
    * 返回值：true or false
    **/
    static function Update($table , $data , $wheresql)
    {
    	$datasql = '';
         //表检查
        $table = self::CheckTable($table);
        //where条件查询
        $where = self::CheckWhere($wheresql);

        //如果是数组格式
        if( is_array($data) )
        {
        	$i = 1;
            foreach($data as $k=>$v)
            {
            	//判断条件是否是数组的特殊语句 array ( '执行符' , '执行条件' , '运算符or或者and' )
            	if ( is_array($v) )
            	{
            		//设置条件的运算符
            		$symbol = array('+','-','*','/');
            		
            		if( !isset($v[1]) )
            		{
            			foreach ($v as $kk=>$vv)
            			{
            				$v[0] = $kk;
            				$v[1] = $vv;
            			}
            		}
					self::$dataPrepare[] = $v[1];
            		//如果条件中存在运算符
            		if( array_intersect($symbol, $v) )
            		{
            			$datasql.=$k.'='.$k." {$v[0]} ?";
            		}
            		else
            		{
            			$datasql.=$k.'= ?';
            		}
            	}
            	else
            	{

					self::$dataPrepare[] = $v;
					$datasql.="`$k`=?";
            	}
                
                //如果不是最后一个指针就加上字段分号
                if( count($data) != $i ){
                	$datasql.=',';
                }
                $i++;
            }
            $sql = "UPDATE ".$table." set ".$datasql.$where;
        }
        else
        {
            $sql = "UPDATE ".$table." set ".$data.$where;
        }
        
        return self::exec( $sql );
    }

    
    /**
    * 作  用：数据库插入操作
    * 参 数1：必须，字符串，表名
    * 参 数2：必须，数组，插入的值
    * 参 数3：选填，布尔值，是否返回sql语句
    * 返回值：true or false
    **/
    static function Insert( $table , $data , $isRs = false)
    {
    	$fields = $values = '';
         //表检查
        $table = self::CheckTable($table);
        
        //如果是数组格式
        if( is_array($data) )
        {
        	$i = 1;
            foreach($data as $k=>$v)
            {

				self::$dataPrepare[] = $v;
                $fields.='`'.$k.'`';
                $values.="?";
                
                //如果不是最后一个指针就加上字段分号
                if( $i < count($data) ){
                    $fields.=',';
                    $values.=',';
                }
                $i++;
            }
            $sql = "INSERT into ".$table."(".$fields.") VALUES(".$values.")";
        }
        else
        {
            $sql = "INSERT into ".$table." VALUES(".$data.")";
        }

    	if( $isRs == false )
		{
			return self::exec( $sql , '1' );
		}
		else
		{
			return $sql;
		}
    }
    /**
    * 作  用：数据库插入多个数组操作
    * 参 数1：必须，字符串，表名
    * 参 数2：必须，数组，插入的值
    * 返回值：true or false
    **/
    static function InsertAll( $table , $data )
    {
		$sqls = '';
		foreach($data as $k=>$v)
		{
			$sqls .= self::Insert($table,$v,true).';';
		}
        return self::exec( $sqls );
	}

    
    /**
    * 作  用：数据库删除操作
    * 参 数1：必须，表名
    * 参 数2：选填，删除的条件，不填则清空表
    * 返回值：true or false
    **/
    static function Delete( $table , $wheresql = '' )
    {
    	$where = '';
        //表检查
        $table = self::CheckTable($table);
        //where条件查询
        if( $wheresql != '' )
        {
        	$where = self::CheckWhere($wheresql);
        }
        $sql = 'delete '.$table.' from '.$table.$where;
        return self::exec( $sql );
    }

    /**
     * 删除表操作
     * 参数1：必须，表名字
     */
    static function Drop($table)
    {
        //表检查
        $table = self::CheckTable($table);
    	return wmsql::exec("DROP TABLE `".$table."`;");
    }
    
    /**
     * 字段递增操作【Increment】
     * @param 参数1，必须，表名
     * @param 参数2，必须，自增的字段
     * @param 参数3，必须，条件
     */
    static function Inc($table, $field , $where )
    {
    	$data[$field] = array('+',1);
    	return self::Update( $table , $data , $where);
    }
    

    /**
     * 字段递减操作【Decrement】
     * @param 参数1，必须，表名
     * @param 参数2，必须，条件
     * @param 参数3，必须，自增的字段
     */
    static function Dec($table, $field , $where )
    {
    	$data[$field] = array('-',1);
    	return self::Update( $table , $data , $where);
    }
    

    /**
    * 作  用：sql错误处理机制
    * 参 数1：必须，错误数组
    * 返回值：true或者结束程序运行
    **/
    static function SqlErr($e)
    {
        $trace_str = '';
        self::Clear();
		//错误日志表检查
		$table = self::CheckTable('@system_errlog');
		
		$rs = self::$db->query("SHOW TABLES LIKE '".$table."'" );
		
		//如果报错了
		if( !method_exists($rs,'fetchAll') )
		{
			self::Close();
			exit;
		}
		else
		{
			$row = $rs->fetchAll();
			if( count($row) == 0 )
			{
	            echo '对不起,数据库表不完整！';
				exit;
			}
			if( $e['file'] <> '' )
			{
				//定义db错误信息
				$dberr = $e['errinfo'];
				$dberr[0] = !isset($dberr[0])?0:$dberr[0];
				$dberr[1] = !isset($dberr[1])?0:$dberr[1];
				$dberr[2] = !isset($dberr[2])?'':$dberr[2];
				$autoUpload = C('config.web.err_auto_upload');
				$strSer = NewClass('str');
				$data['errlog_url'] = $strSer->Escape(GetUrl() , 'e');
				$data['errlog_state'] = $dberr[0];
				$data['errlog_code'] = $dberr[1];
				$data['errlog_msg'] = $strSer->Escape( $dberr[2], 'e');
				$data['errlog_sql'] = $strSer->Escape( $e['sql'] , 'e');
				$data['errlog_ip'] = GetIp();
				$data['errlog_time'] = time();
				if( $autoUpload == '1' )
				{
					$data['errlog_status'] = 1;
				}
				
        		//错误通知，通知出现错误不再重试
                if( !Session('send_warring') )
                {
        	    	Session('send_warring','true');
	        	    $msgData = array(
	        	        'code_eroor_ip'=>str::DelHtml(GetIp()),
	        	        'code_eroor_code'=>$dberr[1],
	        	        'code_eroor_url'=>GetUrl(),
	        	        'code_eroor_sql'=>$e['sql'],
	        	        'code_eroor_time'=>date('Y-m-d H:i:s'),
	        	        'code_eroor_msg'=>$dberr[2]
	                );
        	    	NewClass('msg');msg::SendWarring('warning_code_eroor',$msgData);
                }
                Session('send_warring','delete');
                
				//是否开启了错误日志统计
				if( C('config.web.err_open') == '1')
				{
					$i = 1;
					$fields = $values = '';
					foreach($data as $k=>$v)
					{
						$fields.='`'.$k.'`';
						$values.="'$v'";
				
						//如果不是最后一个指针就加上字段分号
						if( $i < count($data) ){
							$fields.=',';
							$values.=',';
						}
						$i++;
					}
					$sql = "INSERT into ".$table."(".$fields.") VALUES(".$values.")";
					self::$db->exec( $sql );
				}
				//是否开启了自动上传错误
				if( $autoUpload == '1')
				{
					$cloudSer = NewClass('cloud');
					$cloudSer->ErrlogAdd($data);
				}
				
				if ( !DEBUG )
				{
					//前台报错
					if( !WMMANAGER )
					{
						tpl::ErrInfo('页面出错，我们已经记录了该信息！！');
					}
					else
					{
						Ajax('页面出错，我们已经记录了该信息！！',300);
					}
				}
				else if( !file_exists(WMTEMPLATE.'system/wmsql.html') )
				{
					tpl::ErrInfo('对不起,系统模版“wmsql.html”不存在！');
				}
				//如果是ajax请求
				else if( IsAjax() )
				{
					if( !WMMANAGER )
					{
						ReturnData( $dberr[2] );
					}
					else
					{
						Ajax($dberr[2],300);
					}
				}
				//否则输出错误信息
				else
				{
					$eTemp=file_get_contents(WMTEMPLATE.'system/wmsql.html');
	
					//当前错误文件地址替换
					$file = str_replace('\\' , '/' , $e['file'] );
					$file = str_replace( WMROOT , '/' , $file );
					
					//替换系统标签
					$msg = $dberr[2];
					//如果是字段不能为空
					if( str::in_string('Incorrect integer value',$msg,'1') )
					{
						$dberr[2] = $msg  = '<br/>错误原因：官方告知mysql新版本对空值插入有"bug"。<br/>解决办法：在Mysql配置文件my.ini中查找sql-mode
						<br/>默认为sql-mode="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"，  <br/>将其修改为sql-mode="NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"，重启mysql服务即可。';
					}
					if( str::in_string('only_full_group_by',$msg,'1') )
					{
						$dberr[2] = $msg  = '<br/>错误原因：MySQL 5.7.5及以上功能依赖检测功能。<br/>解决办法：在Mysql配置文件my.ini中查找sql-mode
						<br/>移除ONLY_FULL_GROUP_BY，然后重启mysql服务即可。';
					}
					
					$eTemp = str_replace('{sql}',$e['sql'],$eTemp);
					$eTemp = str_replace('{msg}',$msg,$eTemp);
					$eTemp = str_replace('{code}',$dberr[0],$eTemp);
					$eTemp = str_replace('{file}',$file,$eTemp);
					$eTemp = str_replace('{line}',$e['line'],$eTemp);
	
					$i = 1;
					//循环出错误的代码信息
					foreach ($e['trace'] as $keys => $values)
					{
						$file = str_replace('\\','/',$values['file']);
						$file = str_replace(WMROOT,'/',$file);
						$valClass = isset($values['class'])?$values['class']:'';
						//错误代码
						$trace_str.= '<tr class="bg1"><td>'.$i.'</td>
									<td>'.$file.'</td>
									<td>'.$values['line'].'</td>
									<td>'.$values['function'].'</td>
									<td>'.$valClass.'</td></tr>';
						$i++;
					}
					$eTemp = str_replace('{trace}',$trace_str,$eTemp);

					@header('HTTP/1.1 500 Internal Server Error');
					@header("status: 500 Internal Server Error");
					echo $eTemp;
				}
				exit;
			}
		}
    }


    /**
    * 作  用：销毁db
    **/
    static function Close()
    {
        self::$db = null;
    }


    /**
    * 作  用：sql语句安全性检查
    * 参 数1：必须，sql语句
    * 返回值：true or false
    **/
    static function CheckSql($sql)
    {
        //老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        if (strpos($sql, 'union') >0 && preg_match('~(^|[^a-z])union($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用union查询');
        }
        //程序没有使用--,#这样的注释，但是黑客经常使用它们
        elseif (strpos($sql, '/*') > 2 /*|| strpos($sql, '--') >0*/)
        {
            exit('sql语句禁止使用注释');
        }
        //这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        elseif (strpos($sql, 'sleep(') > 0 && preg_match('~(^|[^a-z])sleep($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-sleep(');
        }
        elseif (strpos($sql, 'benchmark') > 0 && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-benchmark');
        }
        elseif (strpos($sql, 'load_file') > 0 && preg_match('~(^|[^a-z])load_file($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-load_file');
        }
        elseif (strpos($sql, 'into outfile') > 0 && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-into outfile');
        }
        elseif (strpos($sql, 'extractvalue') > 0 && preg_match('~(^|[^a-z])extractvalue($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-extractvalue');
        }
        elseif (strpos($sql, 'updatexml') > 0 && preg_match('~(^|[^a-z])updatexml($|[^[a-z])~is', $sql) != 0)
        {
            exit('禁止使用特殊函数查询-updatexml');
        }
        //老版本的MYSQL不支持子查询，但是黑客可以使用它来查询数据库敏感信息
        elseif (preg_match('~\([^)]*?select ~is', $sql) != 0)
        {
        	if ( self::$checkSql === true )
        	{
        		exit('禁止使用子查询');
        	}
        	else
        	{
        		self::$checkSql = true;
        	}
        }
        return $sql;
    }
}
?>