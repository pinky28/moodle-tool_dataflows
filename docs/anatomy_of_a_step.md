# Anatomy of a step

A step defines a single sub-task that gets performed as part of a dataflow. It connects with other steps to form
a dataflow. Steps are defined by step types. See reference for details about the step types.

For any step, connected steps that are placed before it are 'inputs'. Connected steps that are placed
after are 'outputs'. All preceding steps are 'upstream'. All following steps are 'downstream'.

Each step type has its own requirements for the number and type of steps to be connected to. These conditions must be met for the dataflow to be valid.

## Variables

Each step defines a set of variables. They appear in the variable tree under `steps.<alias>`.
The step can access its own variables using 'local referencing'. E.g. `vars.something` is equivalent to
`steps.<alias>.vars.something`.

There is a set of common variables attached to every step

id - Database ID number
name - Name as defined in the settings.
description - Description as defined in the settings
alias - Alais as defined in the settings
depends_on - The step or steps that connect to this step as inputs.
record - (Flow steps only) This contains the current input to the step. After a flow block finishes, this
contains the last input to the step.
iterations - (Flow steps only) The current/last iteration count.

In addition to the common values above, steps can define additional settings which
are available under `steps.<alias>.config`.

Users can also specify user defined variables. These appear under `steps.<alias>.vars`.

## Step type categories

Step types are grouped into categories.

### Triggers
### Connectors
A single execution step.
### Flows
A step that can be executed many times within a flow block, using input given by the previous block.
Except for readers, flow steps can only have flow steps as inputs. They can have connector and/or flow steps
as outputs.

A flow step takes stream data from its input flows, processes them, and provides an output to the next step(s)
in the block.

#### Readers
A flow step that provides a stream for a flow block (i.e. a source). Must appear as the first step in a flow block.
Readers can have connectors as inputs.
#### Writers
A flow step that outputs the stream data (i.e. a sink). 
#### Logic
A flow step that either combines or splits flow branches. 
#### Transformers
A flow step that alters the stream data. It processes the input, and supplies the transformed value as output. 

## Step state

Each step has its own internal state

| State       | Description                                                                                   |
|-------------|-----------------------------------------------------------------------------------------------|
| New         | Step has been created, but not yet initialised.                                               |
| Initialised | Step has been initialised. All steps and variables have been setup. The step is ready to run. |
| Blocked     | Connectors only. Step cannot proceed, waiting on upstream steps.                              |                                                                          
| Waiting     | Flow steps only. Step cannot proceed, waiting on upstream step.                               |
| Processing  | Connectors only. The step is currently running.                                               |
| Flowing     | Flow steps only. The step is currently running.                                               |
| Finished    | The step has finished. Downstreams may still be running.                                      |
| Cancelled   | The step has been cancelled. Downstreams may still be running                                 |
| Finalised   | The dataflow has finished successfully. No further execution will happen.                     |
| Aborted     | The dataflow was terminated prematurely. No further execution will happen.                    |
