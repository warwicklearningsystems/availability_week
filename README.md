# [Warwick term/week availability]

The _Warwick term/week availability_ plugin allows access to activities and resources to be restricted based on 'availability criteria'.  The built-in availability restrictions are date, grade and completion of other activities.  Many courses make use of date restrictions, e.g. 'restrict access to this section until 23rd October 2018'.

## Configuration
To configure the plugin, go to _Site Administration > Plugins > Availability restrictions > Restriction by week._

Currenly all of the configuration will be done in the _textarea_. The requirement is to provide valid JSON input which should consist of JSON objects which in effect will determine the `label`, `date` and `show` values for each restriction option. Example input is shown below:

```
[
  {"label":"Week 1","date":"2018-10-01","show":0},
  {"label":"Week 2","date":"2018-10-08","show":1},
  {"label":"Week 3","date":"2018-10-15","show":1},
  {"label":"Week 4","date":"2018-10-22","show":0},
  {"label":"Week 30","date":"2019-04-01","show":0}
]
```

### Restriction by week JSON Object properties
_Note: although the validity of the complete JSON input is checked, and each object is checked to ensure that it contains the properties described below, the actual validity of the properties themselves are not currently validated._

#### label
Defines the label for the `date`, used when the 'availability criteria' is displayed. For the purposes of this plugin the expectation is that the label will be **Week 1**, **Week 2** etc, but this is not mandatory and the user has the flexibility to give any label of their choosing.

#### date
The actual date, specified in **Y-m-d format**, that will be used in the condition to determine the availability of the activity/resource. This is the most important property which the other properties supplement.

#### show
This property defines whether the `label`/`date` should actually be presented as an option in the 'availability criteria'. The expected input is **1** to **show** the option and **0** to **hide** the option.

_Note: hiding the restriction option will only affect new 'availability criteria' or applied criteria that did not originally contain the concerned option._
