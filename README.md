# xinchengtv
科技星球APP

# XinCheng PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

  
## 官方文档


读文档很重要，请先仔细读读文档 laravel, dingo/api，jwt，fractal 的文档。

查阅[Lumen website](http://lumen.laravel.com/docs).



- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- dingo api 中文文档 [dingo-api-wiki-zh](https://github.com/liyu001989/dingo-api-wiki-zh)
- jwt(json-web-token) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc 生成在线文档 [apidocjs](http://apidocjs.com/)
- rest api 参考规范 [jsonapi.org](http://jsonapi.org/format/)
- 头信息中可以增加 Accept:application/vnd.lumen.v1+json 切换v1和v2版本
##  centos系统上 apidoc 安装
1.  sudo yum install epel-release
2.  sudo yum install nodejs
3.  node --version      （查看下版本别太旧了）
4.  sudo yum install npm 
5.  npm install apidoc -g
6.  apidoc -v           （显示出版本就说明安装成功了）
7.  apidoc -i （源码地址） -o （API文件生成地址）      例子：apidoc -i app/Http/Controllers/Api/V1/  -o public/apidoc/

--安置完成后的生成操作主要看官方文档 http://apidocjs.com/



## 项目环境安装

利用 Composer 来管理自身的依赖
    
    curl -sS https://getcomposer.org/installer | php
    
    php composer.phar  install
    
    chmod -R 777 storage

    JWT_SECRET
     
    php artisan jwt:secret

## 命名规则
<p>1.类的命名规则</p>

<pre><code>使用大写字母作为词的分割，其他的字母均使用小写
名字的首字母使用大写。
</code></pre>

<p>不要使用下划线‘_’。</p>

<pre><code>example:   SuperMan , SuperHero
</code></pre>

<p>2.类的私有属性命名规则</p>

<pre><code>属性命名应该以字符‘m’为前缀。
前缀‘m’后采用与类命名一致的规则。
‘m’在名字的开头起修饰作用，方便查找。

example:   mRedPanties , mSexyMuscle
</code></pre>

<p>3.方法的命名规则</p>

<pre><code>方法命名的前缀需要表明这个方法的作用 一般以英文常用动词开头，（is ，get ，set 等）。

example: isMan , getRedPantiesOfSuperManByName , setSuperHeroSexyMuscle 
</code></pre>

<p>4.方法里参数命名规则   </p>

<pre><code>第一个字符使用小写字母。
在首字符后的所有字符都按照类命名规则首字符大写。

example: $myWord  $userPassword
</code></pre>

<p>5.引用变量命名规则</p>

<pre><code>引用变量要带有‘r’前缀。

example: $rExam 
</code></pre>

<p>6.普通的变量命名规则</p>

<pre><code>所有字母都使用小写。
使用‘_’作为每个词的分界。

example：$msg_error、$chk_pwd等。

临时变量通常被取名为i，j，k，m和n，它们一般用于整型；c，d，e，s 它们一般用于字符型。
实例变量前面需要一个下划线， 首单次小写，其余单词首字母大写。
</code></pre>

<p>7.全局变量</p>

<pre><code>全局变量应该带有前缀‘g’。

example：global $gTest。
</code></pre>

<ol>
<li><p>常量、全局常量</p>

<p>常量、全局常量，应该全部使用大写字母，单词之间用‘_’来分割。</p>

<p>example： DEFAULT_NUM_AVE</p></li>
<li><p>静态变量</p>

<p>静态变量应该带有前缀‘s’。</p>

<p>example：state $sStatus = null;</p></li>
</ol>

<p>10.函数命名</p>

<pre><code>所有的名称都使用小写字母，多个单词使用‘_’来分割。
</code></pre>

<p>&nbsp;&nbsp;&nbsp; <br>
   example：function this_good_idear()</p>

<p>以上规则可组合使用。</p>

<hr>



<h2 id="chengeapi-log-及-异常处理机制-注意事项"> XinChenAIP Log 及 异常处理机制 注意事项 </h2>

<p>1.凡事所有执行 操作数据库的，添加，修改和删除的方法务必纪录执行的mysql语句。 <br>
    Log::Info(); <br>
2.凡事公共方法内必须具备异常处理机制 并纪录log 中。 <br>
    Log::error();</p>

<hr>



<h2 id="chengeapi-gitlab-分支命名版本异常冲突解决方法-及提交备注"> XinChenAPI GitLab 分支命名，版本［异常，冲突］解决方法 及提交备注</h2>

<pre><code>创建个人分支 以 'dev_' 开头 后面接入自己姓名的缩写 加 但前开发功能模块 


 example：
        我叫陈峰目前工作负责维护用户注册模块，创建新的分支命令如下

        git checkout -b dev_cf_user_egister


commit 备注 须备注清楚  修改文件 及 工作内容  指定但前修改的行数

 example：
        今天2016年5月12日 我修改了ConfigurationController.php 文件下的 setRegisteredUsers方法 功能是添加友盟的注册

        git commit -m'添加友盟第三方注册功能，修改文件：ConfigurationController.php -&gt;setRegisteredUsers方法 从第27行开始至100行左右结束'

gitlab仓库出现冲突 注意事项及解决方法
        注意事项 
            1 严禁出现冲突后 脱离gitlab 跟踪机制线下本地导入导出文件。
            2 严禁在代码未经过审查时合并公共分支。
            3 严禁回滚及删除公共分支。
            4 解决冲突时必须与相关开发者当面确定相关代码段。
            5 开发时如果清楚会涉及到别人文件时须跟相关开发者沟通询问具体操作规范
         解决方法
            合并或拉取对方分支时出现冲突文件，首先打开冲突文件 gitlab 冲突代码段标记格式如下
                &lt;&lt;&lt;&lt;&lt;&lt; xxx
                    code...
                ===========    [等于号上面 到［&lt;&lt;&lt;］结束代表你本地分支的代码 等于号下面的代码段 到［&gt;&gt;&gt;&gt;&gt;］代表 你当前拉取分支的代码段]
                    code...
                &gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;xxx
             解决冲突文件时选择保留其中一段或俩者保留,根据和相关开发者沟通结果确定修改完后,删除标记格式,保存,提交到解决冲突的开发者分支后,让相关开发者pull你的分支即可。 
</code></pre>

<p>GitLab 命令 参考资料 <br>
        <a href="http://gitref.org/zh/remotes/">http://gitref.org/zh/remotes/</a></p>

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
