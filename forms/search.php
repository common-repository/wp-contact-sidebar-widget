<form method="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?><?php if ($_REQUEST['edit'] == "True") { echo "&edit=True&page_id={$_REQUEST['page_id']}"; }?>" name="easy_search"> 
<?php if (isset($_REQUEST['page_links'])) { ?>
<input type="hidden" name="page_links" value="True" />
<?php } ?>    
    <table  class="tabForm" width="100%">
        <tr>
        	<td width="17%">Search for:</td>
            <td width="83%"><input type="text" name="search_term" value="<?php echo $_REQUEST['search_term']; ?>" size="50" /> 
            &nbsp;&nbsp;&nbsp;<input type="submit" name="search" value="Search" onclick="return check_form('easy_search');" /></td>
        </tr>
        
        </table> 
</form>
<script>
/* <![CDATA[ */
addToValidate('easy_search','search_term', '', true, 'SEARCH TERM');
/* ]]> */
</script>