<?php
/**
 * ukrainian language file
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Oleksiy Voronin (ovoronin@gmail.com)
 */

$lang['menu'] = 'Керування доданками'; 

// custom language strings for the plugin
$lang['download'] = "Завантажити та встановити новий доданок";
$lang['manage'] = "Встановлені доданки";

$lang['btn_info'] = 'дані';
$lang['btn_update'] = 'обновити';
$lang['btn_delete'] = 'вилучити';
$lang['btn_settings'] = 'параметри';
$lang['btn_download'] = 'Завантажити';
$lang['btn_enable'] = 'Зберегти';

$lang['url']              = 'Адреса';

$lang['installed']        = 'Встановлено:';
$lang['lastupdate']       = 'Останнє оновлення:';
$lang['source']           = 'Джерело:';
$lang['unknown']          = 'невідомо';

// ..ing = header message
// ..ed = success message

$lang['updating']         = 'Оновлення ...';
$lang['updated']          = 'Доданок %s успішно оновлено';
$lang['updates']          = 'Наступні доданки були успішно оновлені';
$lang['update_none']      = 'Оновлення не знайдено.';

$lang['deleting']         = 'Знищення ...';
$lang['deleted']          = 'Доданок %s вилучено.';

$lang['downloading']      = 'Завантаження ...';
$lang['downloaded']       = 'Доданок %s успішно встановлено';
$lang['downloads']        = 'Наступні доданки були успішно встановлені:';
$lang['download_none']    = 'Доданки не знайдено або виникла невідома проблема в процессі завантаження та установки.';

// info titles
$lang['plugin']           = 'Доданок:';
$lang['components']       = 'Компоненти';
$lang['noinfo']           = 'Цей доданок не повідомив ніяких даних, він може бути не працюючим.';
$lang['name']             = 'Назва:';
$lang['date']             = 'Дата:';
$lang['type']             = 'Тип:';
$lang['desc']             = 'Опис:';
$lang['author']           = 'Автор:';
$lang['www']              = 'Сторінка:';
    
// error messages
$lang['error']            = 'Виникла невідома помилка.';
$lang['error_download']   = 'Не можу завантажити файл доданка: %s';
$lang['error_badurl']     = 'Можливо, невірна адреса - не можливо визначити ім\'я файла з адреси';
$lang['error_dircreate']  = 'Не можливо створити тимчасову теку для завантаження';
$lang['error_decompress'] = 'Менеджеру доданків не вдалося розпаковати завантаженний файл. '.
                            'Це може бути результатом помилки при завантаженні, в цьому разі ви можете спробувати знова; '.
                            'або ж доданок упакований невідомим архіватором, тоді вам необхідно '.
                            'завантажити та встановити доданок вручну.';
$lang['error_copy']       = 'Виникла помилка копіювання при спробі установки файлів для доданка '.
                            '<em>%s</em>: переповненя диску або невірні права доступа. '.
                            'Це могло привести до часкової установки доданка и нестійкості '.
                            'вашої Вікі.';
$lang['error_delete']     = 'При спробі вилучення доданка <em>%s</em> виникла помилка.  '.
                            'Найбільш вірогідно, що немає необхідних прав доступа к файлам або текам';

//Setup VIM: ex: et ts=4 enc=utf-8 :
