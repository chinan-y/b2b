	@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" http://www.qqbsmall.com/gcshop/api/mess/input.php",0)(window.close) 
echo 1
taskkill /f /im iexplore.exe 