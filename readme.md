&lt;p&gt;&lt;strong&gt;What does this class do?&lt;/strong&gt;&lt;/p&gt;  
  
&lt;p&gt;This is a simple static PHP class that lets you replicate the CFML (ColdFusion) &lt;cfoutput&gt; &quot;group&quot; method when iterating through queries.&lt;/p&gt;  
  
&lt;p&gt;&lt;strong&gt;What is an example?&lt;/strong&gt;&lt;/p&gt;  
  
&lt;p&gt;Let&#39;s say you have a query that has:&lt;/p&gt;  
  
&lt;pre class="line-numbers  language-markdown" title="Double click to select all"&gt;  
&lt;code class="language-markdown" id="result-code"&gt;| Department  | User    |  
|-------------|---------|  
| Finance     | Judy    |  
| Finance     | Fred    |  
| Finance     | Mary    |  
| Engineering | Lola    |  
| Engineering | Andy    |  
| Engineering | Pete    |  
| Engineering | Jane    |  
| HR          | Frank   |  
| HR          | Heather |&lt;/code&gt;&lt;/pre&gt;  
  
&lt;p&gt;&nbsp;&lt;/p&gt;  
  
&lt;p&gt;You might want to use this query to output a header for each department, then list the users in each department in a bulleted list.&nbsp; Absent some other framework that &quot;does it for you,&quot; this isn&#39;t completely trivial to do in plain PHP code.&nbsp; While you could write code to keep track of when each column changes value, and then remembering to close previous markup/start new markup as it does, it becomes tedious to manage as the number of columns grows.&lt;/p&gt;  
  
&lt;p&gt;This method will convert your two-dimenstional array into a nested array to make it easier to traverse by columns. This is similar to how the CFML &lt;cfoutput&gt; &quot;group&quot; option works.&lt;/p&gt;  
  
&lt;p&gt;&nbsp;&lt;/p&gt;  
  
&lt;p&gt;&lt;strong&gt;How do I run it?&lt;/strong&gt;&lt;/p&gt;  
  
&lt;p&gt;We assume that you&#39;re using PDO to run your query, and the fetchAll() method to put the query results in a two-dimensional array. Let&#39;s call that array&lt;strong&gt; $myQuery&lt;/strong&gt;.&lt;/p&gt;  
  
&lt;p&gt;From there, you can convert your array to a grouped array:&lt;/p&gt;  
  
&lt;p&gt;`$myQuery=PHPGroupQuery::groupQuery($myQuery,[&quot;Department&quot;,&quot;User&quot;]);`&lt;/p&gt;  
  
&lt;p&gt;where the first argument is your query (2D array), and the second argument is an array with the column names you need to group on.&lt;/p&gt;  
  
&lt;p&gt;&nbsp;&lt;/p&gt;  
  
&lt;p&gt;&lt;strong&gt;How do I process the returned array?&lt;/strong&gt;&lt;/p&gt;  
  
&lt;p&gt;Now, you simply loop through the outer array which represents your first grouping level.&nbsp; When you need to hop to a lower grouping level, access the &lt;strong&gt;group &lt;/strong&gt;element of the array, which represents the next level:&lt;/p&gt;  
  
&lt;p&gt;`foreach($myQuery as $record){  
// first grouping level  
echo "&lt;h1&gt;" . $record["department"] . "&lt;/h1&gt;";  
// within each department...  
echo "&lt;ul&gt;";  
foreach($record["group"] as $record){  
// output each user  
echo "&lt;li&gt;" . $record["User"] . "&lt;/li&gt;";  
}  
echo "&lt;/ul&gt;";  
}`