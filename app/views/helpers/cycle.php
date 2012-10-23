<?php 
/*
 * Cycle Helper for cakePHP
 * Copyright (c) 2009 Andreas
 * http://functino.com
 *
 * @author      andreas
 * @version     1.0
 * @license     MIT
 *
 */
 
 /**
 * The CycleHelper is used to cycle through the provided strings. 
 * You can use it for example to set alternate classes for table rows.
 * Usage is simple: 
 * <code>
 *   <table> 
 *   <?php foreach($items as $item):?>
 *     <tr class="<?php echo $cycle->cycle("odd", "even");?>"">
 *       <td><?php echo $item;?></td>
 *     </tr>
 *   <?php endforeach;?>  
 * </code> 
 * 
 * This will create rows where the class alternates between "odd" and "even".
 * 
 * If you need more than one cycle you can use named cycles:
 * <code>
 *   <?php $items = array("Car" => array("VW", "Fiat", "Ford"), "Food" => array("Pizza", "Hotdog"), "..." => array("..."));?> 
 *   <table> 
 *   <?php foreach($items as $item => $aliases):?>
 *     <tr class="<?php echo $cycle->cycle("odd", "even");?>"">
 *       <td>
 *         <?php echo $item;?>: 
 * 	       <?php foreach($aliases as $alias):?>
 *           <div class="<?php echo $cycle->cycle("one", "two", "three", array("name" => "second_cycle"));?>">
 * 				<?php echo $alias;?>
 *           </div>
 *         <?php endforeach?>  
 *      </td>
 *     </tr>
 *   <?php endforeach;?>  
 *   </table>
 * </code>
 *
 */
class CycleHelper extends AppHelper {
 
 /**
  * @var    array
  * @access private
  */	
	private $cycles = array();
 
 
/**
 * Creates a Cycle object. Cycles through elements of an array everytime it is called. This is useful for example if you want to
 * use alternate classes for table rows etc. 
 * This method takes an arbitrary number of strings to cycle through. The last parameter can be an array with options. 
 * If you pass in an options array with the key "name" you create a named cycle. 
 * If you don't pass in this name the name "default" is used. You can use named cycles to use more than one cycle in a loop.
 * @param string $name aribtrary number of strings to cycle through
 * @param array $options Options is an array. Currently only array("name" => "xyz") is implemented
 * @return string $options Link attributes e.g. array('id'=>'selected')
 * @access public 
 */    
    public function cycle()
    {
		$params = func_get_args();
		if(is_array(end($params)))
		{
			$options = array_pop($params);
			$name = $options["name"];
		}
		else
		{
			$name = "default";
		}
		$cycle = $this->getCycle($name);
		if($cycle)
		{
			return $cycle;
		}
		return $this->setCycle($name, $params);	
	}
 
/**
 * Resets a cycle. If you reset a cycle it starts form the first element again. 
 * If you don't pass in a name the default cycle is reset. To reset a named cycle pass in it's name. 
 * @param  string $name Name of the cycle to reset
 * @return Cycle
 * @access public 
 */	
	public function reset($name = "default")
	{
		$this->cycles[$name]->reset();
		return $this;
	}
 
/**
 * Returns the current cycle string. Normally you simply call the cycle() method to get the current cycle string. This current()
 * method is useful if you need to get the current cycle string more than one times. 
 * Pass in a name to get the value of a named cycle.  
 * @param  string $name Name of the cycle
 * @return string The current cycle string
 * @access public 
 */	
	public function current($name = "default")
	{
		return $this->cycles[$name]->current();
	}	
 
/**
 * Creates a ViewCycle and stores it in the cycle array by it's name 
 * @param  string $name Name of the cycle
 * @param  array $params array of cycle strings 
 * @return ViewCycle
 * @access public
 */ 	
	public function setCycle($name, $params)
	{
		$this->cycles[$name] = new ViewCycle($params);
		return $this->cycles[$name];
	}	
 
/**
 * Returns a cycle by it's name 
 * @param  string $name Name of the cycle
 * @return mixed ViewCycle or false
 * @access private
 */ 	
	private function getCycle($name)
	{
		if(!isset($this->cycles[$name]))
		{
			return false;	
		}	
		return $this->cycles[$name];
	}
} 
 
 
/**
 * ViewCycle is used by the Cycle helper to cycle through an array of elements (in most cases: strings)
 */
class ViewCycle
{
/**
 * Holds the name of this Cycle
 * @var    string
 * @access private
 */		
	private $name;
 
/**
 * Holds an array of strings
 * @var    array
 * @access private
 */	
	private $cycle;
 
/**
 * number of cycle strings
 * @var    integer
 * @access private
 */		
	private $count;
 
/**
 * iteration counter
 * @var    string
 * @access private
 */		
	private $i;
 
/**
 * Takes an array of strings to cycle through 
 * @param  array $cycle
 * @return void
 * @access public
 */ 		
	public function __construct($cycle)
	{
		$this->assign($cycle);
	}
 
/**
 * Returns the current cycle string and then sets the next cycle string
 * @return string current cycle string
 * @access public
 */ 		
	public function __toString()
	{
		$this->current = $this->cycle[$this->i % $this->count];
		$this->i++;
		return $this->current;
	}
 
/**
 * Returns the current cycle string 
 * @return string
 * @access public
 */ 		
	public function current()
	{
		return $this->current;
	}
 
/**
 * Resets this cycle
 * @return void
 * @access public
 */ 		
	public function reset()
	{
		$this->i = 0;
	}
 
/**
 * Set an array of strings to cycle through 
 * @param  array $cycle array of strings
 * @return void
 * @access public
 */ 		
	public function assign($cycle)
	{
		$this->cycle = $cycle;
		$this->count = count($this->cycle);
		$this->reset();		
	}
}