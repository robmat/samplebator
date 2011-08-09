#import "ifonly_ipAppDelegate.h"
#import "SplashScreenVC.h"

@implementation ifonly_ipAppDelegate

@synthesize window;


#pragma mark -
#pragma mark Application lifecycle

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    UINavigationController* navCtrl = [[UINavigationController alloc] init];
	window.rootViewController = navCtrl;
	SplashScreenVC* ssvc = [[SplashScreenVC alloc] initWithNibName:nil bundle:nil];
	[navCtrl pushViewController:ssvc animated:NO];
	[ssvc release];
	[self.window makeKeyAndVisible];
    return YES;
}
+ (NSString*)getTempMovieInfoPath {
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES); 
	NSString *documentsDirectoryPath = [paths objectAtIndex:0];
	return [documentsDirectoryPath stringByAppendingPathComponent:@"tempFileInfo.plist"];
}
+ (GDataServiceGoogleYouTube*) getYTService {
	GDataServiceGoogleYouTube* ytService = [[[GDataServiceGoogleYouTube alloc] init] autorelease];
	[ytService setUserCredentialsWithUsername:@"robbator" password:@"robmat666"];
	[ytService setUserAgent:@"ifonly-1.0"];
	return ytService;
}
- (void)applicationWillResignActive:(UIApplication *)application {
}
- (void)applicationDidEnterBackground:(UIApplication *)application {
}
- (void)applicationWillEnterForeground:(UIApplication *)application {
}
- (void)applicationDidBecomeActive:(UIApplication *)application {
}
- (void)applicationWillTerminate:(UIApplication *)application {
}

#pragma mark -
#pragma mark Memory management

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
}
- (void)dealloc {
    [window release];
    [super dealloc];
}
@end
