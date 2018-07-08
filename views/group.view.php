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
<form></form>
<form name="SendGroupMail"
      action="<?php echo add_query_arg(['post_type' => ClubMemberManagement::MAIL_TYPE], admin_url('post-new.php')) ?>"
      method="post">
    <table>
        <tbody>
        <tr>
            <th><input type="checkbox" id="ck_all_members"><label for="ck_all_members">Full Name</label></th>
            <?php
            foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
                if ($field['list'] && $field['key'] != 'member_group') {
                    echo("<th>{$field['label']}</th>");
                }
            }
            ?>
        </tr>
        <?php

        foreach ($data as $member) {
            $meta = get_post_meta($member->ID);
            if ($meta[ClubMemberManagement::MEMBER_TYPE . '_member_group'][0] == $post->ID) {
                echo('<tr>');
                echo("<td><input class='group-member' type='checkbox' name='member_id[]' 
                    value='{$member->ID}'><a href='" .
                    add_query_arg(['post' => $member->ID, 'action' => 'edit'], admin_url('post.php')) . "'>
                    {$member->post_title}</a></td>");
                foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
                    if ($field['list'] && $field['key'] != 'member_group') {
                        $key = ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'];
                        echo("<td>{$meta[$key][0]}</td>");
                    }
                }
                echo('</tr>');
            }
        }

        ?>
        </tbody>
    </table>
    <div style="width: 100%;height: 25px"></div>
    <div style="float:right; margin-top: -22px; margin-right: 2px;">
        <input type="submit" class="button button-secondary button-large" value="Send Group Mail">
    </div>
</form>
<script>
    jQuery("#ck_all_members").click(function () {
        jQuery(".group-member").prop('checked', jQuery("#ck_all_members").prop('checked'));
    });
</script>