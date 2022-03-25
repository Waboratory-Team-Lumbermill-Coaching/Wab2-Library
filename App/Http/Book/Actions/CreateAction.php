<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:55 PM
 */

namespace App\Http\Book\Actions;

use App\Http\Book\BookService;
use App\Models\Genre;
use Base\AbstractAction;
use Base\AbstractService;

class CreateAction extends AbstractAction {
    /**
     * @var BookService
     */
    protected $service;

    public function __construct(AbstractService $service) {
        parent::__construct($service);
    }

    public function __invoke() {
        parent::__invoke();

        $this->renderPage();
    }

    protected function handleData($data) {
        // TODO: Implement handleData() method.
    }

    private function renderPage() {
        $formData = $this->getFormData();

        $genres = $this->service->getGenres();

        $genreOptions = $this->renderGenreOptions($genres);

        \App\Helpers\DrawerHelper::renderPage([
            'title' => 'ADD NEW BOOK',
            'elements' => [
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'a',
                            'attributes' => [
                                'href' => url('my_profile')
                            ],
                            'value' => 'My Profile'
                        ]
                    ]
                ],
                [
                    'tag' => 'form',
                    'attributes' => [
                        'action' => url('add_book'),
                        'method' => 'POST'
                    ],
                    'children' => [
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'label',
                                    'value' => 'Book Title:',
                                ],
                                [
                                    'tag' => 'input',
                                    'attributes' => [
                                        'type' => 'text',
                                        'name' => 'title',
                                        'value' => $formData['title'],
                                    ]
                                ]
                            ],
                        ],
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'label',
                                    'value' => 'Book Author:',
                                ],
                                [
                                    'tag' => 'input',
                                    'attributes' => [
                                        'type' => 'text',
                                        'name' => 'author',
                                        'value' => $formData['author'],
                                    ]
                                ]
                            ],
                        ],
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'label',
                                    'value' => 'Description:',
                                ],
                                [
                                    'tag' => 'textarea',
                                    'attributes' => [
                                        'name' => 'description',
                                    ],
                                    'value' => $formData['description'],
                                ]
                            ],
                        ],
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'label',
                                    'value' => 'Image URL:',
                                ],
                                [
                                    'tag' => 'input',
                                    'attributes' => [
                                        'type' => 'text',
                                        'name' => 'image',
                                        'value' => $formData['image'],
                                    ]
                                ]
                            ],
                        ],
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'label',
                                    'value' => 'Genre:',
                                ],
                                [
                                    'tag' => 'select',
                                    'attributes' => [
                                        'name' => 'genre_id',
                                    ],
                                    'children' => $genreOptions
                                ]
                            ],
                        ],
                        [
                            'tag' => 'div',
                            'children' => [
                                [
                                    'tag' => 'button',
                                    'value' => 'Add',
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ]);
    }

    private function getFormData() {
        $formData = [
            'title' => '',
            'author' => '',
            'description' => '',
            'image' => '',
            'genre' => '',
        ];

        $requestData = $this->getParsedData();
        if(count($requestData)) {
            return $requestData;
        }

        return $formData;
    }

    /**
     * @param Genre[] $genres
     * @return array
     */
    private function renderGenreOptions($genres) {
        return array_map(function(Genre $genre) {
            return [
                'tag' => 'option',
                'attributes' => [
                    'value' => $genre->id
                ],
                'value' => $genre->name
            ];
        }, $genres);
    }
}