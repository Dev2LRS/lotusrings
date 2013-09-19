<?php
/********************************************
  File Name		: paging.php
  Created Date	: Aug 19, 2009

  Modified Date	: Oct 30, 2009
  Notes
  =====
	1. This file contains the paging code.
********************************************/
?>
<style>
.Pagetext
{
	font-family:Arial;
	font-size:11px;
	color:#FF82FF;

	font-weight:bold;
}
.Pagetext1
{
   	font-family:Arial;
	font-size:10px;
	color: #636363;
	font-weight:bold;
}
a.PageLink:link {font-size:13px;font-weight:bold;font-family:Arial;text-decoration:none; width:20px;}
a.PageLink:visited {font-size:13px;font-weight:bold;font-family:Arial;text-decoration:none; width:20px;}
a.PageLink:active {font-size:13px;font-weight:bold;font-family:Arial;text-decoration:none; width:20px;}
a.PageLink:hover {font-size:13px;font-weight:bold;font-family:Arial;text-decoration:none; width:20px;}

a.PageLink { background:url('images/page_normal_bg.png') no-repeat center center; font-size: 13px; color:#636363; text-decoration: none;width:29px;height:29px;line-height:29px;display: inline-block;}
a.PageLink:link { padding: 0 5px;  text-decoration: none;width:20px;}
a.PageLink:hover, a.PageLink:active{ background:url('images/page_hover.png') no-repeat center center; color:#636363; width:20px;line-height:29px; }
.currentpage11{background:url('images/current_page.png') no-repeat center center;line-height:29px;color:#636363 !important;cursor: default;font-size: 12px; width:28px;height:28px; padding: 0 5px;display: inline-block;}

/*.disablelink{background-color: white;cursor: default;color: #929292;border:1px solid #929292;font-weight:normal !important; font-size:11px; }
*/
.disablelink { background:url('images/page_normal_bg.png') no-repeat center center;padding: 0 5px; width:29px;height:29px;line-height:29px;display: inline-block; text-decoration: none;color: #929292;width:20px; font-size:11px;opacity:.5;}
</style>
<table border="0" cellpadding="0" cellspacing="0" >
	<tr><td height='10px'></td></tr>
	<tr>
	<!-- Paging code starts here -->
	<?php
	$CurrentPage=$Page;
	if ($TotalPages > 1)
	{	
	?>
		<td class="Pagetext1" align="center" style='padding:0px 3px 0px 5px;'>PAGE  <B>[ <?php echo $CurrentPage?> of <?php echo $TotalPages?> ]</B></td>
	<?php	
		if($CurrentPage==1)
		{
			echo "<td class='PageLink' align='center' width='30px' style='padding:0px 2px 0px 12px;'><span class='disablelink'>|<</span></td>";
		}
		else
		{
			echo "<td class='Pagetext' align='center' width='30px' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr(1,'$FormName')\" class='PageLink'>|<</a></td>  ";
		}
		$cp=$CurrentPage;

		if($cp<=10)
		{
			echo "<td class='PageLink' align='center' width='20px' style='padding:0px 2px 0px 2px;'><span class='disablelink'><<</span></td>";
		}
		else
		{
			$cp_previous = $cp-10;
			echo "<td class='Pagetext' align='center' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($cp_previous,'$FormName')\" class='PageLink'><<</a></td>";
		}

		if ($cp<=1)
			echo "<td class='PageLink' align='center' width='20px' style='padding:0px 2px 0px 2px;'><span class='disablelink'><</span></td>";
		else
		{
			$cp--;
			echo "<td class='Pagetext' align='center' width='20px' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($cp,'$FormName')\" class='PageLink'><</a></td>";
		}
		for($i=1;$i<=10;$i++)
		{
			$disp_i=$i+(10*floor(($CurrentPage)/10));
			if($CurrentPage>=10)
			{
				$disp_i=$disp_i-5;
				if($CurrentPage>=15 && $CurrentPage%10>=5)
				{
					$disp_i=$disp_i+5;
				}
			}

			if($disp_i<=$TotalPages)
			{
				if($disp_i==$CurrentPage)
					echo "<td class='PageLink' align='center' width='20px' style='padding:0px 2px 0px 2px;'><span class='currentpage11'><b>".$disp_i."</b></span></td>";
				else
				{
					echo "<td align='center' width='20px' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($disp_i,'$FormName')\" class='PageLink' align='center' >$disp_i</a></td>";
				}
			}
		}

		$forward=$CurrentPage;
		if ($forward>=$TotalPages)
			echo "<td class='PageLink' align='center' width='20px' style='padding:0px 2px 0px 2px;'><span class='disablelink'>></span></td>";
		else
		{
			$forward_page=$CurrentPage+1;
			echo "<td class='Pagetext' align='center' width='25px' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($forward_page,'$FormName')\" class='PageLink'>></a></td>";
		}

		if ($forward>=$TotalPages-9)
		{
			echo "<td  align='center' width='20px' height='16px' style='padding:0px 2px 0px 2px;'><span  class='disablelink'>>></span></td> ";
		}
		else
		{
			$forward_next=$forward+10;
			echo "<td class='Pagetext' width='25px' align='center' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($forward_next,'$FormName')\" class='PageLink'>>></a></td>";
		}
		
		if($CurrentPage==$TotalPages)
		{
			echo "<td class='PageLink' align='center' width='20px' style='padding:0px 2px 0px 2px;'><span class='disablelink'>>|</span></td>";
		}
		else
		{
			echo "<td  class='Pagetext' align='center' width='25px' style='padding:0px 2px 0px 2px;'><a href=\"javascript: pageTransferAllrr($TotalPages,'$FormName')\" class='PageLink'>>|</a></td> ";
		}
	}
	?>
	<!-- paging code ends here --> 
	</tr>
	<tr><td height='5px'></td></tr>
</table>

<script>
function pageTransferAllrr(pagenumber,formname)
{
	with(document.forms[formname])
	{		

		HdnPage.value=pagenumber;
		HdnMode.value="paging";
		action.value = "";
		submit();
	}
}
</script>