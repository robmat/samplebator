#import "rhead_sharepoint_ipAppDelegate.h"
#import "SplashVC.h"
#import "MainMenuVC.h"
#import "WebViewVC.h"
#import "AppInfoVC.h"
#import <MessageUI/MessageUI.h>
#import "AccountsVC.h"
#import "ServicesVC.h"

@implementation rhead_sharepoint_ipAppDelegate

@synthesize window, navigationController, tabNavigationController;

static rhead_sharepoint_ipAppDelegate* appDelegate;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
	appDelegate = self;
    self.navigationController = [[UINavigationController alloc] init];
	self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
	window.rootViewController = navigationController;
	SplashVC* svc = [[SplashVC alloc] init];
	[self.navigationController pushViewController:svc animated:YES];
	self.navigationController.viewControllers = [NSArray arrayWithObjects:svc, nil];
    [svc release];
	[self.window makeKeyAndVisible];
    return YES;
}
+ (void)hideTabcontroller: (UIViewController*) nextViewcontroller {
    [appDelegate hideTabcontroller: nextViewcontroller];
}
- (void)hideTabcontroller: (UIViewController*) nextViewcontroller {
    self.navigationController = [[UINavigationController alloc] init];
    self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
    window.rootViewController = navigationController;
    [self.navigationController pushViewController:nextViewcontroller animated:YES];
    [self.window makeKeyAndVisible];
}
+ (void)launchTabcontroller {
    [appDelegate launchTabcontroller];
}
- (void)launchTabcontroller {
    self.navigationController = [[UINavigationController alloc] init];
    self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
    
    AccountsVC* lvc = [[AccountsVC alloc] init];
    AppInfoVC* aivc = [[AppInfoVC alloc] init];
    ServicesVC* svc = [[ServicesVC alloc] init];
    
    UINavigationController* appInfoContrl = [[UINavigationController alloc] init];
    UINavigationController* newsNavContrl = [[UINavigationController alloc] init];
    UINavigationController* servNavContrl = [[UINavigationController alloc] init];
    MFMailComposeViewController* mcvc = [self mailController];
    
    navigationController.tabBarItem = [[UITabBarItem alloc] initWithTitle:@"Main" image:[UIImage imageNamed:@"main_tab.png"] tag:1];
    appInfoContrl.tabBarItem = [[UITabBarItem alloc] initWithTitle:@"App Info" image:[UIImage imageNamed:@"appinfo_tab.png"] tag:2];
    newsNavContrl.tabBarItem = [[UITabBarItem alloc] initWithTitle:@"News" image:[UIImage imageNamed:@"news_tab.png"] tag:3];
    servNavContrl.tabBarItem = [[UITabBarItem alloc] initWithTitle:@"Services" image:[UIImage imageNamed:@"services_tab.png"] tag:4];
    mcvc.tabBarItem = [[UITabBarItem alloc] initWithTitle:@"Contact" image:[UIImage imageNamed:@"contact_tab.png"] tag:5];
    
    WebViewVC* wwvc = [[WebViewVC alloc] init];
    wwvc.url = @"http://www.rheadgroup.com/newshome.asp";
    wwvc.title = @"News";
	wwvc->dontAppendPass = YES;
    
    self.tabNavigationController = [[UITabBarController alloc] init];
    window.rootViewController = tabNavigationController;
    tabNavigationController.delegate = self;
    
    self.tabNavigationController.viewControllers = [NSArray arrayWithObjects:navigationController, mcvc, appInfoContrl, newsNavContrl, servNavContrl, nil];
    [self.window makeKeyAndVisible];
    
    [self.navigationController pushViewController:lvc animated:YES];
    [appInfoContrl pushViewController:aivc animated:YES];
    [newsNavContrl pushViewController:wwvc animated:YES];
    [servNavContrl pushViewController:svc  animated:YES];
    
    self.navigationController.title = @"Main";
    appInfoContrl.title = @"App Info";
    newsNavContrl.title = @"News";
    servNavContrl.title = @"Services";
    
    appInfoContrl.navigationBar.barStyle = UIBarStyleBlack;
    newsNavContrl.navigationBar.barStyle = UIBarStyleBlack;
    servNavContrl.navigationBar.barStyle = UIBarStyleBlack;
    
    [lvc  release];
    [aivc release];
    [wwvc release];
    [svc  release];
    [appInfoContrl release];
    [newsNavContrl release];
    [servNavContrl release];
}

- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultCancelled) {
        self.tabNavigationController.selectedIndex = 0;
		return;
	}
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}
- (MFMailComposeViewController*)mailController {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	if ([MFMailComposeViewController canSendMail] && controller != nil)	{
		controller.mailComposeDelegate = self;
		[controller setToRecipients:[NSArray arrayWithObjects:@"headoffice@rheadgroup.com", nil]];
		[controller setSubject:@""];
		[controller setMessageBody:@"" isHTML:NO]; 
        [controller setTitle:@"Contact"];
		[controller autorelease];
        return controller;
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Can't send mail, probably no email account is set up." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
    return nil;
}
+ (UIImage*)imageWithImage:(UIImage*)image 
			  scaledToSize:(CGSize)newSize
{
	UIGraphicsBeginImageContext( newSize );
	[image drawInRect:CGRectMake(0,0,newSize.width,newSize.height)];
	UIImage* newImage = UIGraphicsGetImageFromCurrentImageContext();
	UIGraphicsEndImageContext();
	
	return newImage;
}
+ (NSString*) accountsPath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString* documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:@"accounts.plist"] ;
}
+ (NSString*) loginDictPath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString* documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:@"loginDict.plist"] ;
}
- (void)applicationWillResignActive:(UIApplication *)application {}

- (void)applicationDidEnterBackground:(UIApplication *)application {}

- (void)applicationWillEnterForeground:(UIApplication *)application {}

- (void)applicationDidBecomeActive:(UIApplication *)application {}

- (void)applicationWillTerminate:(UIApplication *)application {}

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {}

- (void)dealloc {
	[navigationController release];
    [tabNavigationController release];
    [window release];
    [super dealloc];
}


@end
