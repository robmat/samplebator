//
//  DataHandler.m
//  PushViewControllerAnimated
//
//  Created by User on 1/12/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "DataHandler.h"


@implementation DataHandler
- (id) init {
	data = [[NSArray alloc] initWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"all_data" ofType:@"plist"]];
	return self;
}
- (NSMutableArray*) getDataByCategory:(NSString*) category {
	NSMutableArray* tempArr = [[NSMutableArray alloc] init];
	for (NSDictionary* dict in data) {
		NSString* categoryName = [dict objectForKey:@"category"];
		if ([categoryName isEqual:category]) {
			[tempArr addObject:dict];
		}	
	}
	return tempArr;
}
- (void) dealloc {
	[data release];
	[super dealloc];
}	
@end
