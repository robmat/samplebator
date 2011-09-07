#import <UIKit/UIKit.h>

@interface LoginVC : UIViewController {

	IBOutlet UITextField* loginTxt;
	IBOutlet UITextField* passwTxt;
	IBOutlet UITextField* domainTxt;
	
}

@property (nonatomic,retain) IBOutlet UITextField* loginTxt;
@property (nonatomic,retain) IBOutlet UITextField* passwTxt;
@property (nonatomic,retain) IBOutlet UITextField* domainTxt;

- (void) loginAction:(id) sender;

@end
