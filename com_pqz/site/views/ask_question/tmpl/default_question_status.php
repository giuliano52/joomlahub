<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$content = '';
$base_img_path = Juri::base() . 'components/com_pqz/img';

$jinput = JFactory::getApplication()->input;
$question_index = $jinput->get('id_question', 'default_value', 'filter');
?>

<table>
    <tr>
        <?php
        foreach ($_SESSION['pqz_question'] as $id => $single_question) {
            if ($id == $question_index) {
                $extra_stle = "outline: thin solid black; ";
            } else {
                $extra_stle = "";
            }

            if (isset($single_question['answered_question'])) {
                $img_name = "$base_img_path/Circle-question-green.png";
            } else {
                $img_name = "$base_img_path/Circle-question-yellow.png";
            }

            $link = "id_question=$id&task=ask_question&option=";
            ?>

            <td style="text-align: center; <?= $extra_stle ?>" >
                <a href="<?= JRoute::_("index.php?$link") ?>" >
                    <?php
                    $content .= "<td style=\"$extra_stle\"><a href=\"$link\">" . ($id + 1) . "</a></td>";
                    $content .= "<td><a href=\"$link\">";
                    ?>
                    <?= $id + 1 ?>
                    <br>
                    <img src="<?= $img_name ?>">
                </a></td>
            <?php
        }
        ?>
    </tr>
</table>
