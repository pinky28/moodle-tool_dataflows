
| Name                      | Type      | Inputs/Outputs[^1]                         | Description                                            |
|---------------------------|-----------|--------------------------------------------|--------------------------------------------------------|
| Abort                     | Connector |                                            | Aborts the dataflow if the condition evaluates to true |
|                           | Flow      | 1 flow input<br>0-1 connector/flow outputs |                                                        |
| Append file               | Connector |                                            |                                                        |
|                           | Flow      | 1 flow input<br>0-1 connector/flow outputs |                                                        |
| Compression/Decompression | Connector |                                            | [See compression](#compression)                        |
|                           | Flow      | 1 flow input<br>0-1 connector/flow outputs |                                                        |
| Set variable              | Connector |                                            | [See Set variable](#set-variable)                      |

### Compression

Compresses or decompresses files. Currently only supports Gzip. For flows, the filename will typically be taken from
the input.

### Set variable

Allows a variable to be set permanently. The dataflow, as stored in the database, will be updated with the new variable value, and the new value will be available in the next dataflow runs.

Notes:
- The variable is only persisted if the variable is in the dataflow.vars subtree, and only if it is not a dry run.
- The dataflow will not be updated during a dry run, although the variable will still be available for the rest of the run.


[^1]: The most common input/output requirement is 0-1 connector/flow inputs and 0-1 connector/flow outputs.