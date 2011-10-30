#import <UIKit/UIKit.h>
#import "ASIHTTPRequestDelegate.h"
#import "VCBase.h"

@interface LoginVC : VCBase <ASIHTTPRequestDelegate, UITextFieldDelegate> {

	IBOutlet UITextField* loginTxt;
	IBOutlet UITextField* passwTxt;
	IBOutlet UITextField* domainTxt;
	
}

@property (nonatomic,retain) IBOutlet UITextField* loginTxt;
@property (nonatomic,retain) IBOutlet UITextField* passwTxt;
@property (nonatomic,retain) IBOutlet UITextField* domainTxt;

- (IBAction) loginAction:(id) sender;
- (IBAction) accountsAction: (id) sender;

@end
