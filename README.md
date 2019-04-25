# php

**What does this class do?**  

This is a simple static PHP class that lets you replicate the CFML (ColdFusion) &lt;cfoutput&gt; "group" method when iterating through queries.

**What is an example?**

Let's say you have a query that has:

| Department  | User    |
|-------------|---------|
| Finance     | Judy    |
| Finance     | Fred    |
| Finance     | Mary    |
| Engineering | Lola    |
| Engineering | Andy    |
| Engineering | Pete    |
| Engineering | Jane    |
| HR          | Frank   |
| HR          | Heather |

You might want to use this query to output a header for each department, then list the users in each department in a bulleted list.  Absent some other framework that "does it for you," this isn't completely trivial to do in plain PHP code.  While you could write code to keep track of when each column changes value, and then remember to close previous markup/start new markup as it does, it becomes tedious to manage as the number of columns grows.

This method will convert your two-dimensional array into a nested array to make it easier to traverse by columns. This is similar to how the CFML &lt;cfoutput&gt; "group" option works.

**How do I run it?**  

We assume that you're using PDO to run your query (properly sorted), and the fetchAll() method to put the query results in a two-dimensional array. Let's call that array **$myQuery**.

From there, you can convert your array to a grouped array:

`$myQuery=PHPGroupQuery::groupQuery($myQuery,["Department","User"]);`

where the first argument is your query (2D array), and the second argument is an array with the column names you need to group on. You can group to as many levels as you wish.  The query must have at least one record, so test it before you send it for grouping.


**How do I process the returned array?**

Now, you simply loop through the outer array which represents your first grouping level.  When you need to hop to a lower grouping level, access the **group** element of the array, which represents the next level:

    foreach($myQuery as $record){  
        // Department level
        echo "<h1>" .$record["Department]" . "</h1>";  
        echo "<ul>";  
        foreach($record["group"] as $record){  
          // User level
          echo "<li>" . $record["User"] . "</li>";  
        }  
        echo "</ul>"; 
     }  
