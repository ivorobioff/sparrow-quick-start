<?php
namespace ImmediateSolutions\Support\Api;
use ImmediateSolutions\Support\Pagination\PaginationProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Traversable;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Reply
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function blank()
    {
        return $this->responseFactory->create(null, 204);
    }

    /**
     * @param array $data
     * @return ResponseInterface
     */
    public function raw(array $data)
    {
        return $this->responseFactory->create($data, 200);
    }

    /**
     * @param object $item
     * @param callable $serializer
     * @return ResponseInterface
     */
    public function single($item, callable $serializer)
    {
        return $this->responseFactory->create($serializer($item), 200);
    }

    /**
     * @param object[]|array|Traversable $items
     * @param callable $serializer
     * @return ResponseInterface
     */
    public function collection($items, callable $serializer)
    {
        $data = [];

        foreach ($items as $item){
            $data[] = $serializer($item);
        }

        $wrapper = [
            'data' => $data,
            'meta' => []
        ];

        if ($items instanceof PaginationProviderInterface){
            $pagination = $items->getPagination();

            $wrapper['meta']['pagination'] = [
                'total' => $pagination->getTotal(),
                'onPage' => $pagination->getOnPage(),
                'perPage' => $pagination->getPerPage(),
                'current' => $pagination->getCurrent(),
                'totalPages' => $pagination->getTotalPages()
            ];
        }

        return $this->responseFactory->create($wrapper, 200);
    }
}