# Anatomy of a dataflow

A dataflow is made up of a number of steps which are connected to one another. The connections are directional and determine the order of execution. Thus, the dataflow forms a directed acyclic graph.

A dataflow can be invalid while editing, but must be valid in order to run.

## Flow blocks
A group of contiguous flow steps make up a flow block. A flow block is analagous to a for-loop within a program.

The first step in a flow block must be a reader. The reader provides an input that is fed as a stream to the flow block. For each value in the stream, each step in the flow block is executed in sequence.

Each step takes the input, processes it some way, and provides an output. The output of one step becomes the input of the next, so the flow block forms a pipeline.

If a branch occurs, each step afterwards is executed in parallel. If one branch finishes before another, it is put into waiting until all branches have finished, then the next iteration begins.

When there are no more values in the stream, the flow block stops and the execution moves to the following step.

Flow blocks are separated by connectors. If you need to separate two flow blocks but do not want to do anything, you can use a no-op connector.

## Variables

Each dataflow run provides a storage space for data in the form of a variable tree. See Variables for more details.

## Dataflow state

At any time during its execution, a dataflow run is in a certain state. These states are

| State       | Description |
|-------------|-------------|
| New         | Dataflow run has been created, but not yet initialised |
| Initialised | Dataflow has been initialised. All steps and variables have been setup. The dataflow is ready to run. |
| Processing  | The dataflow is currently running. |
| Finished    | All steps have finished running. |
| Finalised   | The dataflow has finished successfully. No further execution will happen. |
| Aborted     | The dataflow was terminated prematurely. |

Once a dataflow run has entered either the Finalised or Aborted state, no further execution is possible.

