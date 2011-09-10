#import <UIKit/UIKit.h>
#import "ASIHTTPRequestDelegate.h"
#import "VCBase.h"

@interface LoginVC : VCBase <ASIHTTPRequestDelegate> {

	IBOutlet UITextField* loginTxt;
	IBOutlet UITextField* passwTxt;
	IBOutlet UITextField* domainTxt;
	
}

@property (nonatomic,retain) IBOutlet UITextField* loginTxt;
@property (nonatomic,retain) IBOutlet UITextField* passwTxt;
@property (nonatomic,retain) IBOutlet UITextField* domainTxt;

- (void) loginAction:(id) sender;

@end
