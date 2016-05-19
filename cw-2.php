<?php
try {
  class Test {
    /* Keeps track of the number of passes.  Cannot be modified externally. */
    protected $passes = 0;

    /* Keeps track of the number of fails.  Cannot be modified externally. */
    protected $fails = 0;

    /* Ensures a "describe" block is not nested in another.  Cannot be modified externally. */
    protected $describing = false;

    /* Describes the tests to be executed.  Wraps the test output in <DESCRIBE::> and <COMPLETEDIN::> */
    public function describe($msg, $fn) {
      /* If describing message is empty or not defined, set a default message */
      if (!$msg) $msg = "The PHP code to be tested and executed";

      /* Start timing the script */
      $start = microtime(true);

      try {
        /* If the current describe block is nested in another describe block, halt the process before any testing occurs */
        if ($this->describing) throw new Exception("cannot call describe within another describe");

        /* Now that a describe block is being used, another describe block cannot be nested inside it */
        $this->describing = true;

        /* Format the "describe" block and run the tests */
        echo "<DESCRIBE::>$msg\n";
        $fn();
      } catch (Exception $e) {
        /* If an error exists, output it to the user */
        echo "<ERROR::>$e\n";
      } finally {
        /* Time the script and round to the nearest millisecond.  Output that to the user. */
        $dur = round((microtime(true) - $start) * 1000);
        echo "<COMPLETEDIN::>$dur\n";

        /* The describe block has ended.  Reset "describing" */
        $this->describing = false;

        /* If any tests failed inside the current "describe" block, throw an error to prevent further execution */
        if ($this->fails > 0) throw new Exception("Failed Tests");
      }
    }
  }
  $test = new Test;
} catch (Exception $e) {
  throw new Exception("Failed to load core API methods");
}
?>
