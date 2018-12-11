# TreeGrid/TreeColumn/PositionColumn 解析

```php
TreeGrid->widget();
	create object TreeGrid
		init
		initColumns 封装了columns的数据

		run
			renderTableHeader
			renderItems
				renderTableRow
					renderDataCell
						renderDataCellContent 实现 $this->columns 的相关数据展示
			renderTableFooter

TreeGrid 		yii\grid\GridView

TreeColumn 		yii\grid\Column default class/icon
PositionColumn 	yii\grid\Column order
```