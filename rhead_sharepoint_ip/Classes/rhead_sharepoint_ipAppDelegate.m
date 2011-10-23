#import "rhead_sharepoint_ipAppDelegate.h"
#import "SplashVC.h"

@implementation rhead_sharepoint_ipAppDelegate

@synthesize window;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
	navigationController = [[UINavigationController alloc] init];
	navigationController.navigationBar.barStyle = UIBarStyleBlack;
	window.rootViewController = navigationController;
	SplashVC* svc = [[SplashVC alloc] init];
	[navigationController pushViewController:svc animated:YES];
	[svc release];
	[self.window makeKeyAndVisible];
    return YES;
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
    [window release];
    [super dealloc];
}


@end
