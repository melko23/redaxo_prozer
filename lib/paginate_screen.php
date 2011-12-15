<?php

class pz_paginate_screen{

	private 
		$list_amount = 10,
		$counter_all,
		$link_vars = array();

	public
		$elements = array(),
		$current_elements = array();	

	public function __construct($elements) 
	{
		$this->elements = $elements;
		$this->current_elements = $elements;
		$this->counter_all = count($elements);
	}

	public function setListAmount($l)
	{
		$this->list_amount = (int) $l;	
	}

	private function getUrl($p,$skip)
	{
		
		$p["linkvars"]["skip"] = $skip;
		
		return "javascript:pz_loadPage('".$p["layer"]."','".
			pz::url($p["mediaview"],$p["controll"],$p["function"],$p["linkvars"])."')";
		
		return pz::url($p["mediaview"],$p["controll"],$p["function"],$p["linkvars"]);
		
		// $p["linkvars"]["skip"] = $prev;
		// pz::url($p["mediaview"],$p["controll"],$p["function"],$p["linkvars"])
		
	}


	public function getPlainView($p = array())
	{
	
		if($this->counter_all < $this->list_amount) return '';
	
		// TODO - Linkmanagement..
		// order
		// skip
		// layer
		// linkvars

	
		$current = rex_request("skip","int",0);
		if($current > $this->counter_all || $current < 0) $current = 0;
		
		$last = (intval($this->counter_all/$this->list_amount)*$this->list_amount)-$this->list_amount;
		$next = $current+$this->list_amount;
		if($next >= $this->counter_all) $next = "";
		$prev = $current-$this->list_amount;
		if($prev < 0) $prev = "";
		
		$page_current = intval($current/$this->list_amount);
		$page_all = intval(($this->counter_all-1)/$this->list_amount);
		
		/*
		$echo .=  'Ergebnissliste'.@$p["first_c"].' bis '.@$p["last_c"].' von '.$this->counter_all.' Treffer';
		 */
		$echo =  '<ul class="pagination">';
		// $echo .=  '<li><a class="page bt7" href="'.pz::url("api","oo",$p).'">erste Seite</a></li>'; // ,"0"
		if($prev !== "") {
		  
		  $echo .=  '<li class="first prev active"><a class="page prev bt5" href="'.$this->getUrl($p,$prev).'"><span class="inner">zurück</span></a></li>'; // $prev
		}else {
		  $echo .=  '<li class="first prev"><a class="page prev bt5 inactive" href="'.pz::url().'"><span class="inner">zurück</span></a></li>';
		}
		
		$show_pages = array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6);
		if($page_all > 6) {
			$show_pages = array();
			$show_pages[0] = 0;
			$show_pages[1] = 1;
			if($page_current<($page_all/3) || $page_current>($page_all/3*2)) {
				$m = (int) ($page_all / 2);
				$show_pages[$m-1] = $m-1;
				$show_pages[$m] = $m;
				$show_pages[$m+1] = $m+1;
			}
			$show_pages[$page_current-1] = $page_current-1;
			$show_pages[$page_current] = $page_current;
			$show_pages[$page_current+1] = $page_current+1;
			$show_pages[$page_all-1] = $page_all-1;
			$show_pages[$page_all] = $page_all;
		}

		if($next !== "") {
		  $echo .=  '<li class="next"><a class="page next bt5" href="'.$this->getUrl($p,$next).'"><span class="inner">vorwärts</span></a></li>'; // $next
		}else {
		  $echo .=  '<li class="next"><a class="page next bt5 inactive" href="'.pz::url().'"><span class="inner">vorwärts</span></a></li>';
		}

		$dot = TRUE;
		for($i=0;$i<=$page_all;$i++) {
			if($page_current == $i) {
		  		$echo .=  '<li><a class="page bt7 active" href="'.$this->getUrl($p,($i*$this->list_amount)).'">'.($i+1).'</a></li>'; // ($i*$this->list_amount)
				$dot = TRUE;
			}elseif(in_array($i,$show_pages)) {
		  		$echo .=  '<li><a class="page bt7" href="'.$this->getUrl($p,($i*$this->list_amount)).'">'.($i+1).'</a></li>'; // ($i*$this->list_amount)
				$dot = TRUE;
			}elseif($dot) {
				$echo .=  '<li><a class="page bt7" href="'.pz::url().'">...</a></li>';
				$dot = FALSE;
			}
		}
		// $echo .=  '<li><a class="page bt7" href="'.pz::url("api","oo",$p).'">letzte Seite</a></li>'; // $last
		
		$echo .=  '</ul>';
		
		$count_from = ($page_current*$this->list_amount)+1;
		$count_to = (($page_current+1)*$this->list_amount);
		if($count_to > $this->counter_all) 
			$count_to = $this->counter_all;
		$text = $count_from.' - '.$count_to.' von '.$this->counter_all.' Treffern';
		
		$echo = '<div class="grid2col setting"><div class="column first">'.$echo.'</div><div class="column last"><ul><li>'.$text.'</li></ul></div></div>';
	
		$this->current_elements = array();
		for($i=$current;$i<($current+$this->list_amount);$i++)
		{
			if(isset($this->elements[$i]))
				$this->current_elements[] = $this->elements[$i];
		}
		
		return $echo;
	}

	public function getCurrentElements()
	{
		return $this->current_elements;
	
	}





}