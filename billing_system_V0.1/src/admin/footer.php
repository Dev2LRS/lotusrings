</td>
</tr>
<tr class="footer">
<td  colspan="3" valign="top" align="center">&copy; Copyright <?php echo date('Y'). " ".SITE_NAME; ?>.</td></tr>
</table>
<!-- <div class="footer" align='left' style="position:fixed;bottom:0px;width:100%">&copy; Copyright <?php echo date('Y'). " ".SITE_NAME; ?>.</div> -->
 </BODY>
<script type="text/javascript">
	$('.close').live('click',function(){
		$(this).parents('.message').fadeOut(1000);
	});
        $('.deleteImg').live('mouseover',function(){
            $(this).attr('src','images/delete_2.png');
        }).live('mouseout',function(){
            $(this).attr('src','images/delete_1.png');
        });
        $('.number').live("keypress",function(event) {
         if(event.which == 8 ||  event.which == 0 ||  event.which == 13)
            return true;
        if  (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });
</script>
</HTML>