<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Result {
	
	protected string $password;
	protected float $score;
	protected float $entropy;
	/**
	 * @var array<Tester_Result>
	 */
	protected array $test_results;
	protected float $calc_duration;
	
	/**
	 * @param string $password
	 * @param float $entropy
	 * @param Tester_Result[] $test_results
	 * @param float $score
	 * @param float $calc_duration
	 */
	public function __construct( string $password, float $entropy, array $test_results, float $score, float $calc_duration )
	{
		$this->password = $password;
		$this->entropy = $entropy;
		$this->test_results = $test_results;
		$this->score = $score;
		$this->calc_duration = $calc_duration;
	}
	
	public function getPassword(): string
	{
		return $this->password;
	}
	
	public function getEntropy(): float
	{
		return $this->entropy;
	}
	
	/**
	 * @return array<Tester_Result>
	 */
	public function getTestResults(): array
	{
		return $this->test_results;
	}
	
	public function getScore(): float
	{
		return $this->score;
	}
	
	public function getCalcDuration(): float
	{
		return $this->calc_duration;
	}
	
}
