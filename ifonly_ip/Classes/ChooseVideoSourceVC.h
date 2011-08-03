#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface ChooseVideoSourceVC : VCBase <UINavigationControllerDelegate, UIImagePickerControllerDelegate> {

}

- (IBAction) cameraAction: (id) sender;
- (IBAction) libraryAction: (id) sender;
- (IBAction) cancelAction: (id) sender;

@end
