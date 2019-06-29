<?php
//SHOPROOO商城资源
namespace app\classes;

class Paginator implements \ArrayAccess, \Illuminate\Support\Contracts\ArrayableInterface, \Countable, \IteratorAggregate, \Illuminate\Support\Contracts\JsonableInterface
{
	protected $pager;
	protected $pageSize;
	protected $total;
	protected $items;

	public function __construct($pager = 'page')
	{
		$this->pager = $pager;
	}

	public function make($items, $total, $pageSize = 10)
	{
		$this->total = abs($total);
		$this->pageSize = $pageSize;
		$this->items = $items;
		return $this;
	}

	public function getCurrentPage($total = NULL)
	{
		$page = abs(input('page', 1));

		if ($total) {
			$this->total = $total;
		}

		(1 <= $page) || ($page = 1);

		if ($this->items) {
			$totalPage = $this->getTotalPage();
			($page <= $totalPage) || ($page = $totalPage);
		}

		return $page;
	}

	public function getTotalPage()
	{
		(0 < $this->pageSize) || $this->pageSize = 10;
		$totalPage = ceil($this->total / $this->pageSize);
		(1 <= $totalPage) || ($totalPage = 1);
		return $totalPage;
	}

	public function links()
	{
		$html = '<ul class="pagination">';
		$totalPage = $this->getTotalPage();
		$currentPage = $this->getCurrentPage();

		if ($totalPage < 10) {
			for ($i = 1; $i <= $totalPage; $i++) {
				$active = ($i == $currentPage ? 'class="active"' : '');
				$html .= '<li ' . $active . '><a href=' . $this->getLink($i) . '>' . $i . '</a></li>';
			}
		}
		else {
			if (3 < $currentPage) {
				$html .= '<li><a href=' . $this->getLink(1) . '>&laquo;</a></li>';
				$start = $currentPage - 2;
			}
			else {
				$start = 1;
			}

			for ($i = $start; $i <= $currentPage; $i++) {
				$active = ($i == $currentPage ? 'class="active"' : '');
				$html .= '<li ' . $active . '><a href=' . $this->getLink($i) . '>' . $i . '</a></li>';
			}

			for ($i = $currentPage + 1; $i <= $currentPage + 3; $i++) {
				$active = ($i == $currentPage ? 'class="active"' : '');
				$html .= '<li ' . $active . '><a href=' . $this->getLink($i) . '>' . $i . '</a></li>';
			}

			if (5 <= $totalPage - $currentPage) {
				$html .= '<li><a href=\'javascript:void(0)\'>...</a></li>';
				$html .= '<li><a href=' . $this->getLink($totalPage) . '>' . $totalPage . '</a></li>';
			}
		}

		return $html .= '</ul>';
	}

	public function getLink($page)
	{
		static $query;

		if (is_null($query)) {
			$query = input();
		}

		$query['page'] = $page;
		return '?' . http_build_query($query);
	}

	public function jsonSerialize()
	{
		return $this->items;
	}

	public function serialize()
	{
		return serialize($this->items);
	}

	public function unserialize($data)
	{
		return $this->items = unserialize($data);
	}

	public function toArray()
	{
		return array_map(function($value) {
			return $value instanceof \Illuminate\Support\Contracts\ArrayableInterface ? $value->toArray() : $value;
		}, $this->items);
	}

	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}

	public function count($mode = COUNT_NORMAL)
	{
		return count($this->items, $mode);
	}

	public function __get($key)
	{
		return $this[$key];
	}

	public function __set($key, $value)
	{
		$this->items[$key] = $value;
	}

	public function __isset($key)
	{
		return isset($this->items[$key]);
	}

	public function __unset($key)
	{
		unset($this->items[$key]);
	}

	public function offsetSet($offset, $value)
	{
		$this->items[$offset] = $value;
	}

	public function offsetExists($offset)
	{
		return isset($this->items[$offset]);
	}

	public function offsetUnset($offset)
	{
		if ($this->offsetExists($offset)) {
			unset($this->items[$offset]);
		}
	}

	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? array_get($this->items, $offset) : null;
	}
}

?>
