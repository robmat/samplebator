//
//  NotifyTemplatesDelegate.m
//  bcm_ip
//
//  Created by User on 3/22/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NotifyTemplatesDelegate.h"
#import "NewNotificationViewController.h"

@implementation NotifyTemplatesDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr itemsArray: (NSArray*) itemsArray {
	for (NSDictionary* dict in itemsArray) {
		for (NSString* key in [dict keyEnumerator]) {
			if ([key isEqual:[NSString stringWithString:@"Id"]] && [[dict objectForKey:key] isEqual:idStr]) {
				NewNotificationViewController* nnvc = [[NewNotificationViewController alloc] init];
				nnvc.templateItem = dict;
				[self.navigationController pushViewController:nnvc animated:YES];
				[nnvc release];
			}
		}
	}
}
- (void) dealloc {
	[navigationController release];
	[super dealloc];
}
@end
