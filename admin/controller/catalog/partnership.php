<?php

/**
 * Класс контроллера для вывода справочника заявок в админке
 *
 * Class ControllerCatalogPartnership
 */
class ControllerCatalogPartnership extends Controller
{

    public function index()
    {

        /**
         * Поключаем language
         */
        $this->load->language('catalog/partnership');

        $this->document->setTitle($this->language->get('heading_title'));

        /**
         * Формируем бредкрамбы
         */
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/partnership', 'user_token=' . $this->session->data['user_token'], true)
        );




        /**
         * Загружаем модель в контроллер
         */
        $this->load->model('catalog/partnership');


        /**
         * Проверяем есть ли в массиве get элемент с ключом field_order
         * (если есть то его значение соответствует полю, по которому мы cортируем)
         * если нет то присваеваем по умолчанию значение id
         * (то есть у нас в случае если не выбрано сортировки по какому-то полю,
         * то сортировка происходит по полю id)
         */
        if (isset($this->request->get['field_order'])) {
            $field_order = $this->request->get['field_order'];
        } else {
            $field_order = 'id';
        }

        /**
         * Проверяем есть ли в массиве get элемент с ключом sort_order
         * (если есть то его значение может быть или asc или desc)
         * если нет то присваеваем по умолчанию значение id
         * (то есть у нас в случае если не выбрано направленности сортировки по какому-то полю,
         * то сортировка происходит по направленности desc)
         */
        if (isset($this->request->get['sort_order'])) {
            $sort_order = $this->request->get['sort_order'];
        } else {
            $sort_order = 'desc';
        }

        /**
         * Присваиваем в переменную $new_order значение направленности сортировки,
         * отличное тому, которое выбрано сейчас
         */
        $new_order = ($sort_order == 'desc') ? 'asc' : 'desc';

        /**
         * Определяем значение фильтров по имени, email и возрасту
         * если форма только отправлена, то берем из массива post
         * если форма отправлена ранее, то берем из массива get,
         * иначе значение фильтра по имени пустое
         */

        if (isset($this->request->post['filter_name'])) {
            $data['filter_name'] = $this->request->post['filter_name'];
        } else if (isset($this->request->get['filter_name'])) {
            $data['filter_name'] = $this->request->get['filter_name'];
            $this->request->post['filter_name'] = $this->request->get['filter_name'];
        } else {
            $data['filter_name'] = '';
        }

        if (isset($this->request->post['filter_email'])) {
            $data['filter_email'] = $this->request->post['filter_email'];
        } else if (isset($this->request->get['filter_email'])) {
            $data['filter_email'] = $this->request->get['filter_email'];
            $this->request->post['filter_email'] = $this->request->get['filter_email'];
        } else {
            $data['filter_email'] = '';
        }

        if (isset($this->request->post['filter_age'])) {
            $data['filter_age'] = $this->request->post['filter_age'];
        } else if (isset($this->request->get['filter_age'])) {
            $data['filter_age'] = $this->request->get['filter_age'];
            $this->request->post['filter_age'] = $this->request->get['filter_age'];
        } else {
            $data['filter_age'] = '';
        }

        /**
         * В переменную $url_part записываем параметры фильтрации, если
         * выбрана фильтрация
         */
        $url_part = '';

        if (isset($this->request->post['filter_name']) && $this->request->post['filter_name']) {
            $url_part .= '&filter_name=' . $this->request->post['filter_name'];
        }
        if (isset($this->request->post['filter_email']) && $this->request->post['filter_email']) {
            $url_part .= '&filter_email=' . $this->request->post['filter_email'];
        }
        if (isset($this->request->post['filter_age']) && $this->request->post['filter_age']) {
            $url_part .= '&filter_age=' . $this->request->post['filter_age'];
        }

        /**
         * Формируем ссылку для сортировки по полю age (текущий контроллер плюс параметры field_order и sort_order)
         * где $new_order - это направленность сортировки, противоположная выбранной,
         * а $url_part - это часть ссылки с параметрами фильтрации (если выбрана фильтрация)
         */
        $data['age_sort_link'] = $this->url->link('catalog/partnership',
            'user_token=' . $this->session->data['user_token'] . '&field_order=age&sort_order=' . $new_order . $url_part);

        /**
         * Формируем ссылку для сортировки по полю name (текущий контроллер плюс параметры field_order и sort_order)
         * где $new_order - это направленность сортировки, противоположная выбранной,
         * а $url_part - это часть ссылки с параметрами фильтрации (если выбрана фильтрация)
         */
        $data['name_sort_link'] = $this->url->link('catalog/partnership',
            'user_token=' . $this->session->data['user_token'] . '&field_order=name&sort_order=' . $new_order . $url_part);

        /**
         * Формируем ссылку для сортировки по полю email (текущий контроллер плюс параметры field_order и sort_order)
         * где $new_order - это направленность сортировки, противоположная выбранной,
         * а $url_part - это часть ссылки с параметрами фильтрации (если выбрана фильтрация)
         */
        $data['email_sort_link'] = $this->url->link('catalog/partnership',
            'user_token=' . $this->session->data['user_token'] . '&field_order=email&sort_order=' . $new_order . $url_part);

        /**
         * Формируем ссылка на наш контроллер для формы фильтров
         */
        $data['filter_action'] = $this->url->link('catalog/partnership',
            'user_token=' . $this->session->data['user_token']);

        /**
         * Обращаемся к методу модели для получения массива заявок из бд,
         * передаем туда параметры сортировки по полю, направленности сортировки
         * и массива фильтрации
         */
        $data['partnerships'] = $this->model_catalog_partnership->getPartnerships($field_order, $sort_order, $this->request->post);

        /**
         * Пройдёмся по массиву и модифицируем его ,
         * добавим к каждой записи сформированную ссылку на контроллер деактивации/ активации
         * и ссылку на контроллер удаления записи из БД
         */
        foreach ($data['partnerships'] as $key => $partnership) {

            /**
             * Ссылка на контроллер активации записи
             * первым аргументом в метод link передаём контроллер - catalog/partners/activate,
             * а во втором формируем набор дополнительных GET- парамметров в этой ссылке:
             * id -айдишник записи в БД
             * user_token - токен текущей сессии
             */
            $data['partnerships'][$key]['activate_link'] =
                $this->url->link(
                    'catalog/partnership/activate',
                    'id=' . $partnership['id'] . '&user_token=' . $this->session->data['user_token'], true
                );

            /**
             * Ссылка на контроллер деактивации записи
             * первым аргументом в метод link передаём контроллер - catalog/partners/deactivate,
             * а во втором формируем набор дополнительных GET-параметров в этой ссылке:
             * id -айдишник записи в БД
             * user_token - токен текущей сессии
             */
            $data['partnerships'][$key]['deactivate_link'] =
                $this->url->link(
                    'catalog/partnership/deactivate',
                    'id=' . $partnership['id'] . '&user_token=' . $this->session->data['user_token'], true
                );
            /**
             * Ссылка на контроллер удаление записи
             * первым аргументом в метод link передаём контроллер - catalog/partners/delete,
             * а во втором формируем набор дополнительных GET-параметров в этой ссылке:
             * id -айдишник записи в БД
             * user_token - токен текущей сессии
             */
            $data['partnerships'][$key]['delete_link'] =
                $this->url->link(
                    'catalog/partnership/delete',
                    'id=' . $partnership['id'] . '&user_token=' . $this->session->data['user_token'], true
                );
            /**
             * Добавим в массив каждой записи поле tr_style:
             * -если в записи полее is_active=1, то формируем строку style для подсветки tr такой записи,
             * иначе - пустая строка
             */
            if ($partnership['is_active'] == 1) {
                $data['partnerships'][$key]['tr_style'] = 'style="background-color: darkseagreen;"';
            } else {
                $data['partnerships'][$key]['tr_style'] = '';
            }
        }
        /**
         * Поключаем контролер header, column_left, footer
         */
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        /**
         * Формируем ответ сервера, загружаем view
         */
        $this->response->setOutput($this->load->view('catalog/partnership_list', $data));
    }

    /**
     * Метод контроллера для удаления записи из БД
     */
    public function delete()
    {
        /**
         * Делаем проверку на наличие в массиве GET параметров id, который
         * соответстует значению поля id в таблице БД
         */
        if (isset($this->request->get['id'])) {
            /**
             * Поключаем модель
             */
            $this->load->model('catalog/partnership');
            /**
             * Вызываем метод модели deletePartner, в который передаём значение id
             */
            $this->model_catalog_partnership->deletePartner($this->request->get['id']);
            /**
             * Делаем редирект обратно на наш основной метод контроллера, чтобы
             * после удаления страница перезагрузилась и отобразился обновлённый список
             */
            $redirect_url = $this->url->link('catalog/partnership',
                'user_token=' . $this->request->get['user_token']);

            $this->response->redirect($redirect_url);

            /*
             * $this->response->redirect($this->url->link('catalog/partnership',
                'user_token=' . $this->request->get['user_token']));
            */
        }
    }

    /**
     * Метод контроллера для активации записи в БД
     * модификация поля is_active, делаем его равным 1
     */
    public function activate()
    {
        if (isset($this->request->get['id'])) {
            /**
             * Подключаем модель
             */
            $this->load->model('catalog/partnership');
            /**
             * Вызываем метод модели activatePartner,
             * в который первым аргументом передаем значение id,
             * а вторым - новое значение поля is_active
             */
            $this->model_catalog_partnership->activatePartner($this->request->get['id'], 1);
            /**
             * Делаем редирект обратно на наш основной метод контроллера, чтобы
             * после модификации поля is_active страница перезагрузилась и отобразился обновлённый список
             */
            $this->response->redirect($this->url->link('catalog/partnership', 'user_token=' . $this->request->get['user_token']));
        }
    }

    /**
     * Метод контроллера для деактивации записи в БД
     * модификация поля is_active, делаем его равным 0
     */
    public function deactivate()
    {
        if (isset($this->request->get['id'])) {
            /**
             * Подключаем модель
             */
            $this->load->model('catalog/partnership');
            /**
             * Вызываем метод модели activatePartner,
             * в который первым аргументом передаем значение id,
             * а вторым - новое значение поля is_active
             */
            $this->model_catalog_partnership->activatePartner($this->request->get['id'], 0);
            /**
             * Делаем редирект обратно на наш основной метод контроллера, чтобы
             * после модификации поля is_active страница перезагрузилась и отобразился обновлённый список
             */
            $this->response->redirect($this->url->link('catalog/partnership', 'user_token=' . $this->request->get['user_token']));
        }
    }
}
