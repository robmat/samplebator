//
//  AssetITInfrastructure.m
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "AssetITInfrastructureDelegate.h"
#import "ItemsViewController.h"
#import "ItemsListViewController.h"

@implementation AssetITInfrastructureDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getItInfrastructureByAsset", @"action", idStr, @"assetId", nil];
	procVC.xmlItemName = [NSString stringWithString:@"ItInfrastructure"];
	[procVC setAccessoryType:UITableViewCellAccessoryNone];
	ilvc.title = NSLocalizedString(@"assetsViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"ITINFRASTRUCTURE_TYPE", @"Type", 
									  @"ITINFRASTRUCTURE_STATUS", @"Status", nil];	
	ilvc.itemsViewController = procVC;
	[self.navigationController pushViewController:ilvc animated:YES];
	[procVC release];
	[ilvc release];
}

- (void) dealloc {
	[navigationController release];
	[super dealloc];
}

@end
