
//Copyright Applicable Ltd 2011

#import "ExcelSheetGenerator.h"

@implementation ExcelSheetGenerator

+ (NSString*) generateExcelXMLFromItems: (NSArray*) arrayOfItems {
	NSArray* keys = [NSArray arrayWithObjects:@"Date", @"Time spent", @"Title", @"Activity type", @"Lesson learnt", @"Description", nil];
	NSMutableString* xmlStr = [NSMutableString stringWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"sheet_template" ofType:@"xml"] encoding: NSUTF8StringEncoding error:nil];
	NSString* row_count = [[NSNumber numberWithInt:([arrayOfItems count] * 7)]  stringValue];
	[xmlStr replaceCharactersInRange:[xmlStr rangeOfString:@"{[<COL_COUNT>]}"] withString:@"2"];
	[xmlStr replaceCharactersInRange:[xmlStr rangeOfString:@"{[<ROW_COUNT>]}"] withString:row_count];
	NSMutableString* table_content = [NSMutableString stringWithString:@""];
	for (NSDictionary* dict in arrayOfItems) {
		for (NSString* key in keys) {
			NSString* val = [dict objectForKey:key];
			if (![key isEqualToString:@"Id"]) {
				[table_content appendString:@"<Row>\n"];
				[table_content appendString:@"<Cell><Data ss:Type=\"String\">"];
				[table_content appendString:key];
				[table_content appendString:@"</Data></Cell>\n"];
				[table_content appendString:@"<Cell><Data ss:Type=\"String\">"];
				[table_content appendString:val];
				[table_content appendString:@"</Data></Cell>\n"];
				[table_content appendString:@"</Row>\n"];
			}
		}
		[table_content appendString:@"<Row></Row>\n"];
	}
	[xmlStr replaceCharactersInRange:[xmlStr rangeOfString:@"{[<TABLE_BODY>]}"] withString:table_content];
	NSLog(@"%@", xmlStr);
	return xmlStr;
}

@end
