<?php

/**
 * Контроллер для формирования страницы "Партнёрство"
 *
 * Class ControllerInformationPartnership
 */
class ControllerInformationPartnership extends Controller
{
    /**
     * Метод для вывода страницы " Партнёрство"
     */
    public function index()
    {
        /**
         * Подключаем к данному контроллеру файл language
         */
        $this->load->language('information/partnership');

        /**
         * Подключаем к данному контроллеру класс модели Catalog
         */
        $this->load->model('catalog/partnership');

        /**
         * Формируем массив бредкрамбов
         */
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/partnership')
        );
        /**
         * Переменная heading_title - заголовок данной страицы, берем из файла language
         */
        $data['heading_title'] = $this->language->get('heading_title');

        /**
         * Подключаем футер
         */
        $data['footer'] = $this->load->controller('common/footer');

        /**
         * Подключаем хедер
         */
        $data['header'] = $this->load->controller('common/header');

        /**
         * Формируем ответ браузеру
         * вызываем метод setOutput объекта Responsen
         * и передаем в него сформированный шаблон
         */
        $this->response->setOutput($this->load->view('information/partnership', $data));
    }
}