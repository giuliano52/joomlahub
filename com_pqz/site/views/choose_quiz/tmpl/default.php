<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<table style="width:100%;">
    <tr>
        <th>Type</th><th>Quiz Name</th><th>Options</th>
    <tr>
    <tr>
        <td>BACK</td>
        <td>
            <a href="javascript: history.go(-1)">Back</a>
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    <?php
    foreach ($this->items as $item) {
        ?>
        <tr class="alt">
            <?php
            if ($item['type'] == 'dir') {
                ?>
                <td><img src="<?= Juri::base() ?>components/com_pqz/img/Blue_folder_seth_yastrov_01-48.png" ></td>
                <td>

                    <A href="<?= JRoute::_("index.php?option=com_pqz&view=choose_quiz&quiz_dir=" . $item['path']); ?>">
                        <?= $item['name'] ?></A>
                </td>
                <?php
            } elseif ($item['type'] == 'file') {

                $quiz_icon = isset($item['quiz_icon']) ? $item['quiz_icon'] : '/img/quiz.png';
                $quiz_icon = Juri::base() . 'components/com_pqz/' . $quiz_icon;
                ?>
                <td><img src = "<?= $quiz_icon ?>" height="48" width="48" ></td>
                <td>

                    <A href = "<?= JRoute::_("index.php?option=com_pqz&task=start_quiz&quiz_conf=" . $item['path']); ?>">
                        <?= $item['title'] ?>
                    </A>
                </td>
                <?php
            }
            ?>
            <td>&nbsp;</td></tr>
        <tr>

            <?php
        }
        ?>
</table>

<div style="font-size: x-small;">
    Version <?= $this->data['version'] ?>
</div>




<?php
// if debug
if ($_SESSION['pqz_configuration']['debug']) {
    print_pre($_SESSION['pqz_configuration']);
    print_pre($_SESSION['pqz_question']);
}
?>