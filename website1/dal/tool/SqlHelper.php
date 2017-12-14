<?php
/**
 * 当前类的命名空间
 */

namespace dal\tool;

/**
 * Class SqlHelper
 * 数据库帮助类
 * @package dal\tool
 * 命名空间
 */
class SqlHelper
{
    /**
     * 连接对象
     * @var null
     */
    static $conn = null;

    /**
     * 预处理对象
     * @var null
     */
    static $stmt = null;


    /**
     * 建立数据库连接
     */
    static function connect()
    {
        //数据库服务器地址
        $servername = "127.0.0.1";
        //数据库用户名
        $username = "root";
        //数据库密码
        $password = "Lcy161926012*";
        try {
            //lcydb 数据库名称
            self::$conn = new \PDO("mysql:host=$servername;dbname=lcydb", $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *执行sql语句进行插入，无返回值
     * @param $sql
     */
    static function exec($sql)
    {
        //调用静态connect方法，建立数据库连接
        self::connect();
        // 使用 exec()执行sql语句，没有结果返回
        self::$conn->exec($sql);
        //关闭连接
        self::$conn = null;
    }

    /**
     * 执行sql语句数组进行批量插入，无返回值
     * @param $sqls
     */
    static function execs($sqls)
    {
        //调用静态connect方法，建立数据库连接
        self::connect();
        // 开始事务
        self::$conn->beginTransaction();
        //$x=array("one","two","three");
        // 遍历sqls语句数组，执行SQL语句
        foreach ($sqls as $sql) {
            //使用 exec()执行sql语句，没有结果返回
            self::$conn->exec($sql);
        }
        // 提交事务
        self::$conn->commit();
        //关闭连接
        self::$conn = null;
    }

    /**
     * 执行预处理语句及绑定参数的sql语句.进行插入或更新
     * 防止 MySQL 注入
     * @param $sql
     * eg:insert into tablename (fieldname1, fieldname2) values (?, ?)
     * @param $pars
     * array("value1","value2");
     * @return mixed
     * 返回影响行数
     */
    static function execute($sql, $pars)
    {
        //如果连接未关闭，先关闭
        self::$conn = null;
        //如果预处理资源未释放，先释放
        self::$stmt = null;
        //调用静态connect方法，建立数据库连接
        self::connect();
        // 预处理 SQL 并绑定参数
        self::$stmt = self::$conn->prepare($sql);
        //遍历参数数组，绑定参数值
        $i = 1;
        foreach ($pars as $par) {
            self::$stmt->bindValue($i, $par);
            $i++;
        }
        //执行sql语句
        self::$stmt->execute();
        //影响行数
        $rs = self::$stmt->rowCount();
        //关闭连接
        self::$conn = null;
        //释放预处理对象资源
        self::$stmt = null;
        //返回影响行数
        return $rs;
    }

    /**
     * 执行查询语句
     * @param $sql
     * eg:select * from tableName where filedname1=? and filename2=?
     * @param null $pars
     * array("value1","value2")
     * @return mixed
     * 查询结果
     */
    static function query($sql, $pars = null)
    {
        //如果连接未关闭，先关闭
        self::$conn = null;
        //如果预处理资源未释放，先释放
        self::$stmt = null;
        //调用静态connect方法，建立数据库连接
        self::connect();
        // 预处理 SQL 并绑定参数
        self::$stmt = self::$conn->prepare($sql);
        //遍历参数数组，绑定参数值
        if ($pars != null) {
            $i = 1;
            foreach ($pars as $par) {
                self::$stmt->bindValue($i, $par);
                $i++;
            }
        }
        //执行sql语句
        self::$stmt->execute();
        /*
        PDO::FETCH_ASSOC	关联索引（字段名）数组形式
        PDO::FETCH_NUM	数字索引数组形式
        PDO::FETCH_BOTH	默认，关联及数字索引数组形式都有
        PDO::FETCH_OBJ	按照对象的形式
        PDO::FETCH_BOUND	通过 bindColumn() 方法将列的值赋到变量上
        PDO::FETCH_CLASS	以类的形式返回结果集，如果指定的类属性不存在，会自动创建
        PDO::FETCH_INTO	将数据合并入一个存在的类中进行返回
        PDO::FETCH_LAZY	 结合了 PDO::FETCH_BOTH、PDO::FETCH_OBJ，在它们被调用时创建对象变量
       */

        /*
        while ($row = self::$stmt->fetch(\PDO::FETCH_OBJ)) {
             var_dump($row);

             echo  json_encode($row) ;
            echo "<br>";
         }
        */
        $row = self::$stmt->fetchAll();
        //echo json_encode($row);
        return $row;
    }


}