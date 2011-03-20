//
//  ProcesDetailDelegate.m
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "ProcessAssetsDelegate.h"
#import "ItemsViewController.h"
#import "AssetITInfrastructureDelegate.h"
#import "ItemsListViewController.h"

@implementation ProcessAssetsDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAssetsByProcess", @"action", idStr, @"processId", nil];
	procVC.xmlItemName = [NSString stringWithString:@"Asset"];
	AssetITInfrastructureDelegate* delegate = [[[AssetITInfrastructureDelegate alloc] init] autorelease];
	delegate.navigationController = self.navigationController;
	[procVC setAccessoryType:UITableViewCellAccessoryDetailDisclosureButton];
	procVC.delegate = delegate;
	ilvc.title = NSLocalizedString(@"assetsViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"ASSET_STATUS_PROBE", @"StatusProbe", 
									  @"ASSET_STATUS", @"Status", 
									  @"ASSET_TYPE", @"Type", nil];	
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
