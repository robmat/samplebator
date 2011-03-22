//
//  NotifyTemplatesDelegate.m
//  bcm_ip
//
//  Created by User on 3/22/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NotifyTemplatesDelegate.h"


@implementation NotifyTemplatesDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr itemsArray: (NSArray*) itemsArray {
	for (NSDictionary* dict in itemsArray) {
		for (NSString* key in [dict keyEnumerator]) {
			if ([key isEqual:[NSString stringWithString:@"Id"]] && [[dict objectForKey:key] isEqual:idStr]) {
				//todo fire up, form feed it with dict
				NSLog(idStr);
			}
		}
	}
}
- (void) dealloc {
	[navigationController release];
	[super dealloc];
}
@end
