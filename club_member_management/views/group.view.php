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
<table>
    <tbody>
    <tr>
        <th>Title</th>
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
            echo("<td><a href='/wp-admin/post.php?post={$member->ID}&action=edit'>{$member->post_title}</a></td>");
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
