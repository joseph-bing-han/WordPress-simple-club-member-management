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
                <option value="">Choose Member</option>
                <?php
                foreach ($members as $group => $group_members) {
                    echo("<optgroup label='{$group}'>");
                    foreach ($group_members as $id => $member) {
                        echo("<option id='option_member_{$id}' value='{$id}' member_name='{$member["full_name"]}'
                            member_mail='{$member["mail_address"]}'>
                            {$member["full_name"]} ({$member['mail_address']})</option>");
                    }
                    echo("</optgroup>");
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
        var id = jQuery('#select_member_list').val();
        if (id != '') {
            if (jQuery('#tr_member_' + id).length == 0) {
                var name = jQuery("#option_member_" + id).attr('member_name');
                var mail = jQuery("#option_member_" + id).attr('member_mail');
                jQuery('#table_group_mail_member').append(
                    '<tr id="tr_member_' + id + '"><input type="hidden" name="member_id[]" value="' + id +
                    '"><td><a href="<?php
                        echo(add_query_arg(['action' => 'edit'], admin_url('post.php')));
                        ?>&post=' + id + '">' + name + '</a></td><td>' + mail +
                    '</td><td><input type="button" class="button button-secondary" value="Remove" ' +
                    'onclick="removeMember(\'' + id + '\')"></td></tr>'
                );
            }
        }
    }
</script>
