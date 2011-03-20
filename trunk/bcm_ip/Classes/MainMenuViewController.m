#import "MainMenuViewController.h"
#import "bcm_ipAppDelegate.h"
#import "LoginViewController.h"
#import "ItemsViewController.h"
#import "ProcessAssetsDelegate.h"
#import "Dictionary.h"
#import "ItemsListViewController.h"
#import "AssetITInfrastructureDelegate.h"

@implementation MainMenuViewController

- (IBAction) logoutAction: (id) sender {
	[[NSFileManager defaultManager] removeItemAtPath:[bcm_ipAppDelegate getLoginDataFilePath] error:nil];
	LoginViewController* loginVC = [[LoginViewController alloc] init];
	[self.navigationController pushViewController:loginVC animated:YES];
	[loginVC release];
}
- (IBAction) supportAction: (id) sender {
	NSURL *url = [NSURL URLWithString:@"http://support.bcmlogic.com/"];
	[[UIApplication sharedApplication] openURL:url];
}
- (IBAction) processesAction: (id) sender {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllProcesses", @"action", nil];
	procVC.xmlItemName = [NSString stringWithString:@"BusinessProcess"];
	ProcessAssetsDelegate* delegate = [[[ProcessAssetsDelegate alloc] init] autorelease];
	delegate.navigationController = self.navigationController;
	procVC.delegate = delegate;
	ilvc.title = NSLocalizedString(@"processesViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"BP_STATUS", @"Status", 
									  @"BP_RTO", @"Rto", 
									  @"BP_CRITICALITY", @"Criticality", 
									  @"BP_TYPE", @"Type", 
									  @"BP_PERIODICITY", @"Pariodicity", nil];
	ilvc.itemsViewController = procVC;
	[procVC setAccessoryType:UITableViewCellAccessoryDetailDisclosureButton];
	[self.navigationController pushViewController:ilvc animated:YES];
	[procVC release];
	[ilvc release];
}	
- (IBAction) assetsAction: (id) sender {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllAssets", @"action", nil];
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
- (IBAction) scenariosAction: (id) sender {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllScenarios", @"action", nil];
	procVC.xmlItemName = [NSString stringWithString:@"Scenario"];
	[procVC setAccessoryType:UITableViewCellAccessoryNone];
	ilvc.title = NSLocalizedString(@"scenariosViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: nil];	
	ilvc.itemsViewController = procVC;
	[self.navigationController pushViewController:ilvc animated:YES];
	[procVC release];
	[ilvc release];
}
- (IBAction) incidentsAction: (id) sender {
	ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllIncidents", @"action", nil];
	procVC.xmlItemName = [NSString stringWithString:@"Incident"];
	[procVC setAccessoryType:UITableViewCellAccessoryNone];
	ilvc.title = NSLocalizedString(@"incidentsViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: nil];	
	ilvc.itemsViewController = procVC;
	[self.navigationController pushViewController:ilvc animated:YES];
	[procVC release];
	[ilvc release];
}

- (IBAction) recoveryAction: (id) sender {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"recoveryAlertTitle", nil) 
													message:nil 
												   delegate:self 
										  cancelButtonTitle:NSLocalizedString(@"cancelLbl", nil) 
										  otherButtonTitles:NSLocalizedString(@"allTasksLbl", nil), NSLocalizedString(@"myTasksLbl", nil), nil];
	[alert show];
	[alert release];
}
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = NSLocalizedString(@"mainMenuFormTitle", nil);
}

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end
