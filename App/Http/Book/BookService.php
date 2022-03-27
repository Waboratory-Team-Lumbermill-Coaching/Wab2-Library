<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:56 PM
 */

namespace App\Http\Book;


use App\Helpers\AppHelper;
use App\Http\Genre\GenreService;
use App\Models\Book;
use Base\AbstractRepository;
use Base\AbstractService;

class BookService extends AbstractService {
    protected $editableFields = [
        'title',
        'author',
        'description',
        'image',
        'genre_id',
    ];

    /**
     * @var GenreService
     */
    protected $genreService;

    public function __construct(
        BookRepository $repository,
        GenreService   $genreService
    ) {
        parent::__construct($repository);
        $this->genreService = $genreService;
    }

    /**
     * @param array $data
     * @return Book
     * @throws \Exception
     */
    public function create(array $data) {
        if(!AppHelper::authUser()){
            return null;
        }
        $data = $this->sanitize($data);
        $book = new Book();


        foreach ($data as $key => $item) {
            $book->{$key} = $item;
        }

        $book->user_id = AppHelper::authUser()->id;
        $this->repository->store($book);

        return $book;
    }

    /**
     * @return \App\Models\Genre[]
     */
    public function getGenres() {
        return $this->genreService->index();
    }

    /**
     * @param null $query
     * @return Book[]
     */
    public function index($query = null) {
        $books = $this->repository->findAll($query);

        foreach ($books as $book) {
            $book->genre = $this->genreService->getById($book->genre_id);
        }

        return $books;
    }
}