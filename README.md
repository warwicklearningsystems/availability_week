# [Warwick term/week availability]

The _Warwick term/week availability_ plugin allows access to activities and resources to be restricted based on 'availability criteria'.  The built-in availability restrictions are date, grade and completion of other activities.  Many courses make use of date restrictions, e.g. 'restrict access to this section until 23rd October 2018'.

## Configuration
**IMPORTANT: This plugin must be configured as soon as it's deployed, ensure that all entries are correct before releasing for use. It's advisable to configure this plugin for as many future academic years as possible. This will ensure that restrictions are correctly applied when courses are copied to the next academic year. Once restrictions have been applied refrain from changing label values or removing items from the config, _note: if this does occur user access to any activity/resource that should be restricted by this label will be prevented by default_ .**

To configure the plugin, go to _Site Administration > Plugins > Availability restrictions > Restriction by week._

Currenly all of the configuration will be done in the _textarea_. The requirement is to provide valid JSON input which should consist of JSON objects which in effect will determine the `label`, `academic_year`, `date` and `show` values for each restriction option. Example input is shown below:

```
[
  {"label":"Start of 18/19","academic_year":"none","date":"2018-10-01","show":1},
  {"label":"Start of 19/20","academic_year":"none","date":"2019-09-30","show":1},
  {"label":"Week 1","academic_year":"18/19","date":"2018-10-01","show":0},
  {"label":"Week 2","academic_year":"18/19","date":"2018-10-08","show":1},
  {"label":"Week 3","academic_year":"18/19","date":"2018-10-15","show":1},
  {"label":"Week 4","academic_year":"18/19","date":"2018-10-22","show":0},
  {"label":"Week 30","academic_year":"18/19","date":"2019-04-01","show":0},
  {"label":"Week 1","academic_year":"19/20","date":"2018-09-30","show":1},
]
```
#### Defaults

In cases where a course does not fall within an academic year, i.e. it's {name} (fullname/shortname) is not in the format, {name} (yy/yy), or there is no config for an academic year course; which given the example config above, they'd be no conifg found for CES Potsgraduate Study Skills (17/18), the default restriction options will instead be presented. Default options can be set in the config by setting `"academic_year"::"none"` as shown in the first two lines of the config above.

### Restriction by week JSON Object properties
_Note: although the validity of the complete JSON input is checked, and each object is checked to ensure that it contains the properties described below, only the value of the `date` property is currently validated._

#### label
Defines the label for the `date`, used when the 'availability criteria' is displayed. For the purposes of this plugin the expectation is that the label will be **Week 1**, **Week 2** etc, but this is not mandatory and the user has the flexibility to give any label of their choosing.

#### academic_year
The value is expected to be specified in **yy/yy format**. This is an important property as it used to determine the restriction options. The value is used in the pattern match against the course->fullname or course->shortname properties, a successful match results in the option being made available.

#### date
The actual date, specified in **Y-m-d format**, that will be used in the condition to determine the availability of the activity/resource. This is the most important property which the other properties supplement.

#### show
This property defines whether the `label`/`date` should actually be presented as an option in the 'availability criteria'. The expected input is **1** to **show** the option and **0** to **hide** the option.

_Note: hiding the restriction option will only affect new 'availability criteria' or applied criteria that did not originally contain the concerned option._

## Course copy

It's possible to **copy 'week availability'** restrictions from one academic year course (source) to another (destination). But to ensure this works correctly, first ensure that config exists for the (destination) course. For example, given **CES Potsgraduate Study Skills (19/20)**, prior to copying this course **to CES Potsgraduate Study Skills (20/21), ensure that `academic year` 19/20 is cloned across to  `academic year` 20/21 in the config**.
