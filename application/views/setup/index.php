<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" lang="en" xml?ns="http://www.w3.org/1999/xhtml"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Setup Dominater</title> 
    <style type="text/css">
    body { text-align:center}
    </style> 
    </head> 
<body> 
    <h1>Setup Dominater</h1> 
	<?=form_open('setup/process')?>
    <table border="1" align="center"> 
        <tr>
			<td>Database Name</td>
			<td><input type="text" name="database[database]" /></td> 
        </tr> 
        <tr>
			<td>Database Username</td>
			<td><input type="text" name="database[username]" /></td> 
        </tr>
		<tr>
			<td>Database Password</td>
			<td><input type="text" name="database[password]" /></td> 
        </tr>
		<tr>
			<td colspan="2" ><input type="submit" name="submit" /> <input type="reset" name="reset" /></td> 
        </tr>
        </table>
	<?=form_close()?>
    </body> 
</html> 
