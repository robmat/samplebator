#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>

@interface rhead_sharepoint_ipAppDelegate : NSObject <UIApplicationDelegate, MFMailComposeViewControllerDelegate, UITabBarControllerDelegate> {
    UIWindow *window;
	IBOutlet UINavigationController* navigationController;
    IBOutlet UITabBarController *tabNavigationController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet UINavigationController *navigationController;
@property (nonatomic, retain) IBOutlet UITabBarController *tabNavigationController;

+ (NSString*) accountsPath;
+ (NSString*) loginDictPath;
+ (UIImage*) imageWithImage:(UIImage*)image scaledToSize:(CGSize)newSize;
+ (void)launchTabcontroller;
- (void)launchTabcontroller;
+ (void)hideTabcontroller: (UIViewController*) nextViewcontroller;
- (void)hideTabcontroller: (UIViewController*) nextViewcontroller;
- (MFMailComposeViewController*)mailController;

@end

