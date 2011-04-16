#import "MainMenuViewController.h"
#import "bcm_ipAppDelegate.h"
#import "LoginViewController.h"
#import "ItemsViewController.h"
#import "ProcessAssetsDelegate.h"
#import "Dictionary.h"
#import "ItemsListViewController.h"
#import "AssetITInfrastructureDelegate.h"
#import "NotifyTemplatesDelegate.h"
#import "CacheManager.h"

@implementation MainMenuViewController

static int RECOVERY_ALERT_TAG = 1;
static int NOTIFY_ALERT_TAG = 2;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    BOOL ipad = UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad;
	if ((self = [super initWithNibName:ipad ? @"IPadMainMenuViewController" : nibNameOrNil bundle:nibBundleOrNil])) {
    }
    return self;
}
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
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"SCENARIO_STATUS", @"Status", nil];	
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
	alert.tag = RECOVERY_ALERT_TAG;
	[alert show];
	[alert release];
}
- (void) alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (alertView.tag == RECOVERY_ALERT_TAG) {	
		NSString* actionStr = nil;
		if (buttonIndex == 1) {
			actionStr = [NSString stringWithString:@"getAllTasks"];
		} else if (buttonIndex == 2) {
			actionStr = [NSString stringWithString:@"getTasksByUser"];
		} else {
			return;
		}
		ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
		ItemsViewController* procVC = [[ItemsViewController alloc] init];
		procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: actionStr, @"action", nil];
		procVC.xmlItemName = [NSString stringWithString:@"RecoveryTask"];
		[procVC setAccessoryType:UITableViewCellAccessoryNone];
		ilvc.title = NSLocalizedString(@"allTasksViewTitle", nil);
		procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
		procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
										  @"RT_STATUS", @"Status", 
										  @"RT_TYPE", @"Type", nil];	
		ilvc.itemsViewController = procVC;
		[self.navigationController pushViewController:ilvc animated:YES];
		[procVC release];
		[ilvc release];
	}
	if (alertView.tag == NOTIFY_ALERT_TAG) {
		if (buttonIndex == 1) {
			ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
			ItemsViewController* procVC = [[ItemsViewController alloc] init];
			procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllNotifyTemplates", @"action", nil];
			procVC.xmlItemName = [NSString stringWithString:@"NotifyTemplate"];
			NotifyTemplatesDelegate* delegate = [[[NotifyTemplatesDelegate alloc] init] autorelease];
			delegate.navigationController = self.navigationController;
			procVC.delegate = delegate;
			ilvc.title = NSLocalizedString(@"notifyTmeplatesViewTitle", nil);
			procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
			procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: nil];
			ilvc.itemsViewController = procVC;
			[procVC setAccessoryType:UITableViewCellAccessoryDetailDisclosureButton];
			[self.navigationController pushViewController:ilvc animated:YES];
			[procVC release];
			[ilvc release];
		} else if (buttonIndex == 2) {
			ItemsListViewController* ilvc = [[ItemsListViewController alloc] initWithNibName:@"ItemsViewController" bundle:nil];
			ItemsViewController* procVC = [[ItemsViewController alloc] init];
			procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllCallLogs", @"action", nil];
			procVC.xmlItemName = [NSString stringWithString:@"CallLog"];
			[procVC setAccessoryType:UITableViewCellAccessoryNone];
			ilvc.title = NSLocalizedString(@"callLogsViewTitle", nil);
			procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
			procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: nil];	
			ilvc.itemsViewController = procVC;
			[self.navigationController pushViewController:ilvc animated:YES];
			[procVC release];
			[ilvc release];
		} else {
			return;
		}
	}	
	
}
- (IBAction) notifyAction : (id) sender {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"notifyAlertTitle", nil) 
													message:nil 
												   delegate:self 
										  cancelButtonTitle:NSLocalizedString(@"cancelLbl", nil) 
										  otherButtonTitles:NSLocalizedString(@"notifyBtnLbl", nil), NSLocalizedString(@"monitorBtnLbl", nil), nil];
	alert.tag = NOTIFY_ALERT_TAG;
	//[alert show];
	[alert release];
	NSArray* res = [cm getAssetsByProcessId:@"154"];
	NSLog(@"%@", [res description]);
}
- (void) viewDidLoad {
    [super viewDidLoad];
	self.title = NSLocalizedString(@"mainMenuFormTitle", nil);
	cm = [[CacheManager alloc] init];
	[cm fillInCachesOverwrite: YES];
}

- (void) didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void) viewDidUnload {
    [super viewDidUnload];
}


- (void) dealloc {
    [super dealloc];
	[cm release];
}


@end
