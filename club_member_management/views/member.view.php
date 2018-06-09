<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-6-9 上午11:00
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */
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
            echo("<select {$field["custom_attribute"]} id='{$field["key"]}' name='{$field["key"]}'>");
            foreach ($member_groups as $group) {
                $selected = $group->ID == $data[$field["key"]] ? "selected='selected'" : "";
                echo("<option value='{$group->ID}' {$selected}>{$group->post_title}</option>");
            }
            echo "</select>";
    }

}
