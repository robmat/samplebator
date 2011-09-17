#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "ASIHTTPRequestDelegate.h"

@interface LoginVC : VCBase <ASIHTTPRequestDelegate> {
	IBOutlet UITextField* loginTxt;
	IBOutlet UITextField* passwTxt;
}

@property(nonatomic,retain) IBOutlet UITextField* loginTxt;
@property(nonatomic,retain) IBOutlet UITextField* passwTxt;

- (void)loginAction: (id) sender;
- (void)registerAction: (id) sender;
- (void)forgotAction: (id) sender;

@end
