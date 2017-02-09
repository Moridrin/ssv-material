<?php
/**
 * Template for displaying search forms in Twenty Sixteen
 *
 * @package Moridrin
 * @subpackage SSV
 * @since SSV 1.0
 */
?>

<form role="search" method="get" class="search-form" action="/">
    <div class="input-field">
        <input id="search" type="search" value="<?= get_search_query() ?>" name="s" required>
        <label for="search"><i class="material-icons">search</i></label>
        <i class="material-icons">close</i>
    </div>
</form>