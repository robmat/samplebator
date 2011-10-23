#import "ifonly_ipAppDelegate.h"
#import "SplashScreenVC.h"

@implementation ifonly_ipAppDelegate

@synthesize window;

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
+ (GDataServiceGoogleYouTube*) getYTServiceWithcredentials: (BOOL) withCredentials {
	NSDictionary* accountDict = [NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"account" ofType:@"plist"]];
	NSString* uName = [accountDict objectForKey:@"ytAccountName"];
	NSString* uPass = [accountDict objectForKey:@"ytAccountPass"];
	NSString* devKey = [accountDict objectForKey:@"ytDevKey"];
	GDataServiceGoogleYouTube* ytService = [[[GDataServiceGoogleYouTube alloc] init] autorelease];
	if (withCredentials) {
		[ytService setUserCredentialsWithUsername:uName password:uPass];
		[ytService setYouTubeDeveloperKey:devKey];
	}
	[ytService setUserAgent:@"ifonly-1.0"];
	NSLog(@"Created YT service: %@", [ytService description]);
	NSLog(@"Account dictionary: %@", [accountDict description]);
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
- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
}
- (void)dealloc {
    [window release];
    [super dealloc];
}
@end
