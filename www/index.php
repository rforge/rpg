<!DOCTYPE html>
<!-- saved from url=(0014)about:internet -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="x-ua-compatible" content="IE=9" >

<title>rpg</title>

<style type="text/css">
body, td {
   font-family: sans-serif;
   background-color: white;
   font-size: 12px;
   margin: 8px;
}

tt, code, pre {
   font-family: 'DejaVu Sans Mono', 'Droid Sans Mono', 'Lucida Console', Consolas, Monaco, monospace;
}

h1 { 
   font-size:2.2em; 
}

h2 { 
   font-size:1.8em; 
}

h3 { 
   font-size:1.4em; 
}

h4 { 
   font-size:1.0em; 
}

h5 { 
   font-size:0.9em; 
}

h6 { 
   font-size:0.8em; 
}

a:visited {
   color: rgb(50%, 0%, 50%);
}

pre {	
   margin-top: 0;
   max-width: 95%;
   border: 1px solid #ccc;
   white-space: pre-wrap;
}

pre code {
   display: block; padding: 0.5em;
}

code.r, code.cpp {
   background-color: #F8F8F8;
}

table, td, th {
  border: none;
}

blockquote {
   color:#666666;
   margin:0;
   padding-left: 1em;
   border-left: 0.5em #EEE solid;
}

hr {
   height: 0px;
   border-bottom: none;
   border-top-width: thin;
   border-top-style: dotted;
   border-top-color: #999999;
}

@media print {
   * { 
      background: transparent !important; 
      color: black !important; 
      filter:none !important; 
      -ms-filter: none !important; 
   }

   body { 
      font-size:12pt; 
      max-width:100%; 
   }
       
   a, a:visited { 
      text-decoration: underline; 
   }

   hr { 
      visibility: hidden;
      page-break-before: always;
   }

   pre, blockquote { 
      padding-right: 1em; 
      page-break-inside: avoid; 
   }

   tr, img { 
      page-break-inside: avoid; 
   }

   img { 
      max-width: 100% !important; 
   }

   @page :left { 
      margin: 15mm 20mm 15mm 10mm; 
   }
     
   @page :right { 
      margin: 15mm 10mm 15mm 20mm; 
   }

   p, h2, h3 { 
      orphans: 3; widows: 3; 
   }

   h2, h3 { 
      page-break-after: avoid; 
   }
}

</style>





</head>

<body>
<h1>rpg</h1>

<p>An R package for reading from and writing to a PostgreSQL database</p>

<p>If you are tired of struggling with the database packages in R and use only PostgreSQL,
this is the package for you. No more juggling connection and result objects. Just issue
the query and get the results back as a data frame.</p>

<h2>Key features include:</h2>

<ol>
<li><p>Simple quick query execution and retrieval</p>

<pre><code>library(rpg)
res = fetch(&quot;select * from mtcars&quot;) # query default database
class(res)                          # data.frame
</code></pre>

<p>Note that any command that requires a connection will attempt to make a connection if one does not exisit; first by resetting the connection, in case its failed, and then attemping
to use the default connection values. In practice this usually mean you just load the
library and start issuing queries. This is intented for interactive use. If you need to
write a database translation engine, there are other packages for that. (Why you would
do this in R is another question.)</p></li>
<li><p>Simple access to parameterized queries. This means no more escaping query strings!
If you don&#39;t know what that means, you will quickly find out if you start using R
database packages.</p>

<pre><code>library(rpg)
connect(&quot;dbname = testing&quot;)                            # use any libpq connection string
query(&quot;select * from testtab where a = $1&quot;, &quot;yes&quot;)     # the value &quot;yes&quot; is substituted for $1
res = fetch()                                          # returns the query as data.frame
res = fetch(&quot;select * from testtab where a = \&#39;yes\&#39;&quot;) # escaped version
</code></pre></li>
<li><p>Simple access to database cursors. This is the way you were supposed to interact
with your database. Basically a cursor is a prepared query that return a limited number
of rows on demand. This is conveniently wrapped in an R iterator from the iterators
package.</p>

<pre><code>library(rpg)
library(foreach)
r3 = cursor(&quot;select * from testtab&quot;, by = 3)
x = foreach(i = r3, .combine = rbind) %do%
{
    colSums(i)   # sum over 3 rows at a time
}
</code></pre>

<p>Here neither the client nor the server deals with more than three rows of results
at a time. This allows incremental processing of massive tables. Note that you <strong>can</strong>
do parallel computing with <code>cursor</code>. See <code>help(&quot;cursor&quot;)</code> for an example.</p></li>
<li><p>Simple access to execution of prepared statements.</p>

<pre><code>library(rpg)
prepare(&quot;insert into mytab (a, b, c) values ($1, $2, $3)&quot;)
params = matrix(rnorm(300), 100)
execute_prepared(params)
</code></pre>

<p>The call to <code>execute_prepared</code> evalutes the prepared statement for each row of the
supplied parameters. This evaluation loop is in C++.</p></li>
<li><p>Maintain multiple live connections without holding external pointers, creating
finalizers, etc.</p>

<pre><code>library(rpg)
connect(&quot;dbname = db1&quot;); push_conn()
connect(&quot;dbname = db2&quot;); push_conn()
show_conn_stack(); rotate_conn_stack()
swap_conn(); pop_conn(); pop_conn()
</code></pre>

<p>This means there are no connection objects to save and reload as invalid null pointers. All state lives only for the current session.</p></li>
<li><p>Use the asynchronous query interface of <code>libpq</code>.</p>

<pre><code>library(rpg)
connect()
# eg some big join
async_query(&quot;select bigtab1.id from bigtab1, bigtab2&quot;)
Sys.sleep(5)
res = switch(async_status(),
             BUSY = { cancel(); NULL },
             PGRES_TUPLES_OK = fetch(),
             NULL)
finish_async()
</code></pre>

<p>The <code>async_status</code> call will return <code>BUSY</code> condition if the server is still working.
A normal status string is returned if results are ready. <code>DONE</code> is returned if there
is nothing more to fetch. You can attempt to cancel a command in progress using <code>cancel</code>.</p></li>
</ol>

<h2>Installation</h2>

<p>You wil need to install <a href="http://www.postgresql.org/download/">libpq</a> as a requirement.
The configure step will call <a href="http://www.postgresql.org/docs/9.1/static/app-pgconfig.html">pg_config</a>.</p>

<p>If typing</p>

<pre><code>system(&quot;pg_config --version&quot;)
</code></pre>

<p>in R does not return anything, then this package will likely not install. To install in R, try</p>

<pre><code>install.packages(c(&quot;devtools&quot;, &quot;Rcpp&quot;, &quot;roxygen2&quot;))
devtools::install_github(&quot;rpg&quot;, &quot;thk686&quot;)
</code></pre>

</body>

</html>

