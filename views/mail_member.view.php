<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-6-9 下午1:01
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */
?>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    th {
        background-color: #33b3db;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
<table id="table_group_mail_member">
    <tbody>
    <tr>
        <th>Full Name</th>
        <th>Email Address</th>
        <th>
            <select id="select_member_list">
                <option value="">Choose Group</option>
                <?php
                foreach ($groups as $group_id => $group_name) {
                    echo("<option value='{$group_id}'>{$group_name}</option>");
                }
                ?>
            </select>
            <input type="button" class='button button-secondary' onclick="addMember();" value="Add Member">
        </th>
    </tr>
    <?php
    foreach ($data as $member) {
        $mail_address = get_post_meta($member->ID, ClubMemberManagement::MEMBER_TYPE . '_email');
        echo("<tr id='tr_member_{$member->ID}'>");
        echo("<input type='hidden' name='member_id[]' value='$member->ID'>");
        echo("<td><a href='" .
            add_query_arg(['post' => $member->ID, 'action' => 'edit'], admin_url('post.php')) . "'>
                    {$member->post_title}</a></td>");
        echo("<td>{$mail_address[0]}</td>");
        echo("<td><input type='button' class='button button-secondary' value='Remove'
            onclick='removeMember(\"{$member->ID}\")'></td>");
        echo('</tr>');
    }
    ?>
    </tbody>
</table>
<script>
  function removeMember(id) {
    jQuery("#tr_member_" + id).remove();
  }

  function addMember() {
    var all_members = <?php echo json_encode($members);?>;
    const id = jQuery('#select_member_list').val();
    if (id != '' && typeof(all_members[id]) != 'undefined') {
      const members = all_members[id];
      for (let tid in members) {
        if (tid > 0 && jQuery('#tr_member_' + tid).length == 0) {
          const name = members[tid]['full_name'];
          const mail = members[tid]['mail_address'];
          jQuery('#table_group_mail_member').append(
            '<tr id="tr_member_' + tid + '"><input type="hidden" name="member_id[]" value="' + tid +
            '"><td><a href="<?php
                echo(add_query_arg(['action' => 'edit'], admin_url('post.php')));
                ?>&post=' + tid + '">' + name + '</a></td><td>' + mail +
            '</td><td><input type="button" class="button button-secondary" value="Remove" ' +
            'onclick="removeMember(\'' + tid + '\')"></td></tr>'
          );
        }
      }

    }
  }
</script>
