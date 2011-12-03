#import <UIKit/UIKit.h>

@interface rhead_sharepoint_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	//UINavigationController* navigationController;
    IBOutlet UITabBarController *navigationController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet UITabBarController *navigationController;

+ (NSString*) accountsPath;
+ (NSString*) loginDictPath;
+ (UIImage*) imageWithImage:(UIImage*)image scaledToSize:(CGSize)newSize;

@end

