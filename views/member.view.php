<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-6-9 上午11:00
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
<?php
// Output label and field
foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {

// Output label and field
    echo('<label for="{$field["key"]}"><h4>' . __($field['label'] . ':', CMM_KEY) . '</h4></label>');
    if ($field['type'] == 'input') {

    }
    switch ($field['type']) {
        case 'input':
            echo("<input {$field["custom_attribute"]} id='{$field["key"]}' name='{$field["key"]}' value='{$data[$field["key"]]}'");
            break;
        case 'textarea':
            echo("<textarea {$field["custom_attribute"]} id='{$field["key"]}' name='{$field["key"]}'>{$data[$field["key"]]}</textarea>");
            break;
        case 'member_group':
            $ids = $data[$field['key']];
            $groups = [];
            $group_select = "<select id='select_group_list' name='{$field["key"]}'>
             <option value=''>Choose Group</option>";
            foreach ($member_groups as $group) {
                $group_select .= "<option value='{$group->ID}'>{$group->post_title}</option>";
                $groups[$group->ID] = $group->post_title;
            }
            $group_select .= "</select>";

            ?>
            <table id="table_member_group" style="width: 500px;">
                <tbody>
                <tr>
                    <th>Group Name</th>
                    <th>
                        <?php echo $group_select ?>
                        <input type="button" class='button button-secondary' onclick="addGroup();" value="Add Group">
                    </th>
                </tr>
                <?php
                foreach ($ids as $id) {
                    if (!empty($id)) {
                        $group_link = add_query_arg(['post' => $id, 'action' => 'edit'], admin_url('post.php'));
                        echo <<<EOF
                <tr id="tr_group_{$id}">
                    <input type="hidden" name="member_group[]" value="{$id}">
                    <td><a href="{$group_link}">{$groups[$id]}</a></td>
                    <td><input type="button" class="button button-secondary" value="Remove" onclick="removeGroup('{$id}')"></td>
                </tr>
EOF;
                    }
                }
                ?>
                </tbody>
            </table>
            <script>
              function removeGroup(id) {
                jQuery("#tr_group_" + id).remove();
              }

              function addGroup() {
                var id = jQuery('#select_group_list').val();
                var name = jQuery('#select_group_list').find("option:selected").text();
                if (id != '') {
                  if (jQuery('#tr_group_' + id).length == 0) {
                    jQuery('#table_member_group').append(
                      '<tr id="tr_group_' + id + '"><input type="hidden" name="member_group[]" value="' + id +
                      '"><td><a href="<?php
                          echo(add_query_arg(['action' => 'edit'], admin_url('post.php')));
                          ?>&post=' + id + '">' + name +
                      '</a></td><td><input type="button" class="button button-secondary" value="Remove" ' +
                      'onclick="removeGroup(\'' + id + '\')"></td></tr>'
                    );
                  }
                }
              }
            </script>
        <?php
    }

}
